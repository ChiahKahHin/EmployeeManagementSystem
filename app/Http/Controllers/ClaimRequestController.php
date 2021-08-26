<?php

namespace App\Http\Controllers;

use App\Mail\ClaimRequestMail;
use App\Models\ClaimType;
use App\Models\ClaimRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ClaimRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('employeeAndManager');
    }

    public function applyBenefitClaimForm()
    {
        $claimTypes = ClaimType::all();
        $approvedClaims = ClaimRequest::all()
                        ->where('claimEmployee', Auth::user()->id)
                        ->whereIn('claimStatus', [0, 2]);

        return view('applyBenefitClaim', ['claimTypes' => $claimTypes, 'approvedClaims' => $approvedClaims]);
    }

    public function applyBenefitClaim(Request $request)
    {
        $this->validate($request, [
            'claimType' => 'required',
            'claimAmount' => 'required',
            'claimDate' => 'required|before:today-1',
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
        $claimRequest->claimEmployee = Auth::user()->id;
        $claimRequest->save();

        $email = $claimRequest->getReportingManager();
        
        Mail::to($email)->send(new ClaimRequestMail($claimRequest));

        return redirect()->route('applyBenefitClaim')->with('message', 'Benefit claim applied successfully!');
    }

    public function manageClaimRequest()
    {
        if(Auth::user()->isAdmin()){
            $claimRequests = ClaimRequest::all();
        }
        elseif(Auth::user()->isHrManager() || Auth::user()->isManager()){
            $claimRequests = ClaimRequest::where('claimEmployee', Auth::user()->id)->orWhere('claimManager', Auth::user()->id)->get();
        }
        else{
            $claimRequests = ClaimRequest::all()->where('claimEmployee', Auth::user()->id);
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

        return view('viewClaimRequest', ['claimRequest' => $claimRequest]);
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
        $claimRequest->save();

        Mail::to($claimRequest->getEmployee->email)->send(new ClaimRequestMail($claimRequest, $reason));

        return redirect()->route('viewClaimRequest', ['id' =>$id]);
    }
}
