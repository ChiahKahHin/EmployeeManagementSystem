<?php

namespace App\Http\Controllers;

use App\Mail\DelegationMail;
use App\Models\Delegation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class DelegationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['employee:admin,hrmanager,manager']);
    }

    public function addDelegationForm()
    {
        $managers = User::with('getDepartment')->where('role', '!=', 3)->where('id', '!=', Auth::id())->orderBy('role', 'DESC')->get();

        return view('addDelegation', ['managers' => $managers]);
    }

    public function addDelegation(Request $request)
    {
        $this->validate($request, [
            'delegateManagerID' => 'required',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'reason' => 'required|max:255',
        ]);

        //Check whether the start and end date got conflict with another approval delegation
        $checkDelegationDateConflict = Delegation::where('managerID', Auth::id())
                                                   ->whereIn('status', [0,1])
                                                   ->where(function ($query) use ($request){
                                                       $query->where('startDate', '<=', $request->startDate)
                                                             ->where('endDate', '>=', $request->startDate)
                                                             ->orWhere('startDate', '<=', $request->endDate)
                                                             ->where('endDate', '>=', $request->endDate)
                                                             ->orWhere('startDate', '>=', $request->startDate)
                                                             ->where('endDate', '<=', $request->endDate);
                                                   })
                                                   ->count();

        if($checkDelegationDateConflict > 0){
            return redirect()->route('addDelegation')->with('error', 'Conflict approval delegation is found!')
                                                     ->with('error1', 'Please select another period');
        }
        
        //Check whether the selected delegate manager is on another approval delegation
        $checkDelegationDateConflict = Delegation::where('managerID', $request->delegateManagerID)
                                                   ->whereIn('status', [0,1])
                                                   ->where(function ($query) use ($request){
                                                       $query->where('startDate', '<=', $request->startDate)
                                                             ->where('endDate', '>=', $request->startDate)
                                                             ->orWhere('startDate', '<=', $request->endDate)
                                                             ->where('endDate', '>=', $request->endDate)
                                                             ->orWhere('startDate', '>=', $request->startDate)
                                                             ->where('endDate', '<=', $request->endDate);
                                                   })
                                                   ->count();

        if($checkDelegationDateConflict > 0){
            return redirect()->route('addDelegation')->with('error', 'Conflict approval delegation is found!')
                                                     ->with('error1', 'Please select another delegate manager');
        }

        $delegation = new Delegation();
        $delegation->managerID = Auth::id();
        $delegation->delegateManagerID = $request->delegateManagerID;
        $delegation->reason = $request->reason;
        $delegation->startDate = $request->startDate;
        $delegation->endDate = $request->endDate;
        if($request->startDate == date('Y-m-d')){
            $delegation->status = 1;
            $users = User::where('reportingManager', Auth::id())->get();
            foreach ($users as $user) {
                $user->delegateManager = $request->delegateManagerID;
                $user->save();
            }
        }
        else{
            $delegation->status = 0;
        }
        $delegation->save();
        
        Mail::to($delegation->getDelegateManager->email)->send(new DelegationMail($delegation));

        return redirect()->route('addDelegation')->with('message', 'Approval delegation added successfully!')
                                                 ->with('message1', 'An email notification will be sent to the new delegate manager.');
    }

    public function manageDelegation()
    {
        $delegations = Delegation::with('getDelegateManager')->where('managerID', Auth::id())->orderBy('status', 'ASC')->orderBy('startDate', 'ASC')->get();

        return view('manageDelegation', ['delegations' => $delegations]);
    }

    public function cancelDelegation($id)
    {
        $delegation = Delegation::find($id);
        if($delegation->status == 0){
            $delegation->status = 3;
            $delegation->save();
        }
        else{
            $users = User::where('reportingManager', Auth::id())->get();
            foreach ($users as $user) {
                $user->delegateManager = null;
                $user->save();
            }
            $delegation->status = 4;
            $delegation->save();
        }
        Mail::to($delegation->getDelegateManager->email)->send(new DelegationMail($delegation));

        return redirect()->route('manageDelegation');
    }

    public function viewDelegation($id)
    {
        $delegation = Delegation::find($id);

        return view('viewDelegation', ['delegation' => $delegation]);
    }

    public function deleteDelegation($id)
    {
        $delegation = Delegation::find($id);
        $delegation->delete();

        return redirect()->route('manageDelegation');
    }
}
