<?php

namespace App\Http\Controllers;

use App\Mail\CarriedForwardLeaveMail;
use App\Models\CarriedForwardLeave;
use App\Models\CarriedForwardLeaveRule;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CarriedForwardLeaveRuleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['employee:Admin,hrManager'])->only(['manageCarriedForwardLeaveForm', 'manageCarriedForwardLeave']);
    }

    public function manageCarriedForwardLeaveForm()
    {
        $rule = CarriedForwardLeaveRule::get()->first();
        $annualLeaveLimit = LeaveType::where('leaveType', 'Annual Leave')->get('leaveLimit')->first();

        return view('manageCarriedForwardLeave', ['rule' => $rule, 'annualLeaveLimit' => $annualLeaveLimit]);
    }

    public function manageCarriedForwardLeave(Request $request){
        // $this->validate($request, [
        //     'leaveLimit' => 'required',
        //     'useBefore' => 'required',
        //     'startDate' => 'required',
        //     'endDate' => 'required',
        // ]);

        $rule = CarriedForwardLeaveRule::find(1);
        if($request->ableCF == 1){
            $rule->ableCF = 1;
        }
        else{
            $rule->ableCF = 0;
        }
        if($request->recurring == 1){
            $rule->recurring = 1;
        }
        else{
            $rule->recurring = 0;
        }
        $rule->leaveLimit = $request->leaveLimit;
        $rule->useBefore = $request->useBefore;
        $rule->startDate = $request->startDate;
        $rule->endDate = $request->endDate;
        if($request->approval == 1){
            $rule->approval = $request->approval;
        }
        else{
            $rule->approval = 0;
        }
        $rule->save();

        return redirect()->route('manageCarriedForwardLeave')->with('message', 'Carried forward leave configuration updated successfully!');
    }

    public function applyCarriedForwardLeaveForm()
    {
        $count = CarriedForwardLeave::where('employeeID', Auth::id())->whereYear('created_at', date('Y'))->get()->count();
        $carriedForwardLeaveApplication = CarriedForwardLeave::where('employeeID', Auth::id())->whereYear('created_at', date('Y'))->get()->first();
        if($count > 0){
            return redirect()->route('viewCarriedForwardLeave', ['id' => $carriedForwardLeaveApplication->id]);
        }

        $rule = CarriedForwardLeaveRule::get()->first();

        $approvedLeaves = LeaveRequest::select('leaveDuration')
                                        ->where('employeeID', Auth::user()->id)
                                        ->where('leaveType', 1)
                                        ->where('leaveStatus', 2)
                                        ->whereYear('leaveStartDate', date('Y'))
                                        ->get();

        $oriAnnualLeave = LeaveType::select('leaveLimit')
                                     ->where('id', 1)
                                     ->get()
                                     ->first();

        return view('applyCarriedForwardLeave', ['rule' => $rule, 'approvedLeaves' => $approvedLeaves, 'oriAnnualLeave' => $oriAnnualLeave]);
    }

    public function applyCarriedForwardLeave(Request $request)
    {
        $rule = CarriedForwardLeaveRule::get()->first();

        $carriedForwardLeave = new CarriedForwardLeave();
        $carriedForwardLeave->employeeID = Auth::id();
        if($rule->approval == 1){
            $carriedForwardLeave->managerID = Auth::user()->reportingManager;
            if(Auth::user()->delegateManager != null){
                $carriedForwardLeave->delegateManagerID = Auth::user()->delegateManager;
            }
        }
        $carriedForwardLeave->leaveLimit = $request->leaveLimit;
        $carriedForwardLeave->useBefore = $rule->useBefore;
        if($rule->approval == 1){
            $carriedForwardLeave->status = 0;
            $message = "Carried Forward Leave Request Submitted!";
            $message1 = "An email notification will be sent to manager for approval purpose";
        }
        else{
            $carriedForwardLeave->status = 2;
            $message = "Carried Forward Leave Applied Successfully!";
            $message1 = "No approval is needed from the manager";
        }
        $carriedForwardLeave->save();
        if($rule->approval == 1){
            if($carriedForwardLeave->delegateManagerID == null){
                Mail::to($carriedForwardLeave->getManager->email)->send(new CarriedForwardLeaveMail($carriedForwardLeave));
            }
            else{
                Mail::to($carriedForwardLeave->getDelegateManager->email)->send(new CarriedForwardLeaveMail($carriedForwardLeave));
            }
        }

        return redirect()->route('viewCarriedForwardLeave', ['id' => $carriedForwardLeave->id])
                         ->with('message', $message)
                         ->with('message1', $message1);
    }

    public function viewCarriedForwardLeave($id)
    {
        $carriedForwardLeave = CarriedForwardLeave::find($id);

        return view('viewCarriedForwardLeave', ['carriedForwardLeave' => $carriedForwardLeave]);
    }

    public function approveCFRequest($id){
        $carriedForwardLeave = CarriedForwardLeave::find($id);
        $carriedForwardLeave->status = 2;
        $carriedForwardLeave->save();

        Mail::to($carriedForwardLeave->getEmployee->email)->send(new CarriedForwardLeaveMail($carriedForwardLeave));

        return redirect()->route('viewCarriedForwardLeave', ['id' => $id]);
    }

    public function rejectCFRequest($id, $reason){
        $carriedForwardLeave = CarriedForwardLeave::find($id);
        $carriedForwardLeave->status = 1;
        $carriedForwardLeave->rejectedReason = $reason;
        $carriedForwardLeave->save();

        Mail::to($carriedForwardLeave->getEmployee->email)->send(new CarriedForwardLeaveMail($carriedForwardLeave, $reason));
        
        return redirect()->route('viewCarriedForwardLeave', ['id' => $id]);
    }

    public function manageCarriedForwardLeaveRequest(){
        if(Auth::user()->isAccess('admin')){
            $carriedForwardLeaves = CarriedForwardLeave::with('getEmployee.getEmployeeInfo', 'getManager.getEmployeeInfo')->orderBy('useBefore', 'DESC')->get();
        }
        elseif(Auth::user()->isAccess('hrmanager', 'manager')){
            $carriedForwardLeaves = CarriedForwardLeave::with('getEmployee.getEmployeeInfo', 'getManager.getEmployeeInfo')
                                                        ->where('managerID', Auth::id())
                                                        ->orWhere('delegateManagerID', Auth::id())
                                                        ->orderBy('useBefore', 'DESC')
                                                        ->get();
        }

        return view('manageCarriedForwardLeaveRequest', ['carriedForwardLeaves' => $carriedForwardLeaves]);
    }

    public function deleteCFRequest($id){
        $carriedForwardLeave = CarriedForwardLeave::find($id);
        $carriedForwardLeave->delete();

        return redirect()->route('manageCarriedForwardLeaveRequest');
    }
}
