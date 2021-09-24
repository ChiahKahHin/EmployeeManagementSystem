<?php

namespace App\Http\Controllers;

use App\Mail\ClaimRequestMail;
use App\Models\ClaimType;
use App\Models\ClaimRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ClaimRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function applyBenefitClaimForm()
    {
        $claimTypes = ClaimType::with('getClaimCategory')->get();
        $approvedClaims = ClaimRequest::with('getClaimType')
                        ->where('claimEmployee', Auth::user()->id)
                        ->whereIn('claimStatus', [0, 2])->get();

        return view('applyBenefitClaim', ['claimTypes' => $claimTypes, 'approvedClaims' => $approvedClaims]);
    }

    public function applyBenefitClaim(Request $request)
    {
        $this->validate($request, [
            'claimType' => 'required',
            'claimAmount' => 'required',
            'claimDate' => 'required|before:tomorrow',
            'claimDescription' => 'required|max:65535',
            'claimAttachment' => 'required|file',
        ]);
        
        $claimRequest = new ClaimRequest();
        $claimRequest->claimType = $request->claimType;
        $claimRequest->claimAmount = $request->claimAmount;
        $claimRequest->claimDate = $request->claimDate;
        $claimRequest->claimDescription = $request->claimDescription;
        $claimRequest->claimAttachment = file_get_contents($request->claimAttachment);
        $claimRequest->claimStatus = 0;
        $claimRequest->claimManager = Auth::user()->reportingManager;
        if(Auth::user()->delegateManager != null){
            $claimRequest->claimDelegateManager = Auth::user()->delegateManager;
        }
        $claimRequest->claimEmployee = Auth::user()->id;
        $claimRequest->save();
        
        if($claimRequest->claimDelegateManager == null){
            Mail::to($claimRequest->getManager->email)->send(new ClaimRequestMail($claimRequest));
        }
        else{
            Mail::to($claimRequest->getDelegateManager->email)->send(new ClaimRequestMail($claimRequest));
        }

        return redirect()->route('applyBenefitClaim')->with('message', 'Benefit claim applied successfully!');
    }

    public function manageClaimRequest()
    {
        if(Auth::user()->isAdmin() || Auth::user()->isHrManager()){
            $claimRequests = ClaimRequest::with('getClaimType', 'getEmployee')->get();
        }
        elseif(Auth::user()->isManager()){
            $claimRequests = ClaimRequest::with('getClaimType', 'getEmployee')
                                          ->where('claimEmployee', Auth::user()->id)
                                          ->orWhere('claimManager', Auth::user()->id)
                                          ->orWhere('claimDelegateManager', Auth::user()->id)
                                          ->get();
        }
        else{
            $claimRequests = ClaimRequest::with('getClaimType', 'getEmployee')->where('claimEmployee', Auth::user()->id)->get();
        }

        return view('manageClaimRequest',['claimRequests' => $claimRequests]);
    }

    public function deleteClaimRequest($id)
    {
        $claimRequest = ClaimRequest::findOrFail($id);
        $claimRequest->delete();

        return redirect()->route('manageClaimRequest');
    }

    public function viewClaimRequest($id)
    {
        $claimRequest = ClaimRequest::findOrFail($id);

        if(Auth::user()->isAccess('admin', 'hrmanager') || Auth::id() == $claimRequest->claimEmployee || Auth::id() == $claimRequest->claimManager || Auth::id() == $claimRequest->claimDelegateManager){
            return view('viewClaimRequest', ['claimRequest' => $claimRequest]);
        }
        else{
            return redirect()->route('manageClaimRequest');
        }
    }

    public function approveClaimRequest($id)
    {
        $claimRequest = ClaimRequest::find($id);
        $claimRequest->claimStatus = 2;
        $claimRequest->save();

        Mail::to($claimRequest->getEmployee->email)->send(new ClaimRequestMail($claimRequest));

        return redirect()->route('viewClaimRequest', ['id' =>$id]);
    }

    public function rejectClaimRequest($id, $reason)
    {
        $claimRequest = ClaimRequest::find($id);
        $claimRequest->claimStatus = 1;
        $claimRequest->claimRejectedReason = $reason;
        $claimRequest->save();

        Mail::to($claimRequest->getEmployee->email)->send(new ClaimRequestMail($claimRequest, $reason));

        return redirect()->route('viewClaimRequest', ['id' =>$id]);
    }

    public function cancelClaimRequest($id)
    {
        $claimRequest = ClaimRequest::find($id);
        $claimRequest->claimStatus = 3;
        $claimRequest->save();

        if($claimRequest->claimDelegateManager == null){
            Mail::to($claimRequest->getManager->email)->send(new ClaimRequestMail($claimRequest));
        }
        else{
            Mail::to($claimRequest->getDelegateManager->email)->send(new ClaimRequestMail($claimRequest));
        }

        return redirect()->route('viewClaimRequest', ['id' => $id]);
    }
}
