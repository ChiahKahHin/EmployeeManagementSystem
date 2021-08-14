<?php

namespace App\Http\Controllers;

use App\Models\BenefitClaim;
use App\Models\ClaimRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClaimRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('employeeAndManager');
    }

    public function applyBenefitClaimForm()
    {
        $benefitClaims = BenefitClaim::all();
        $approvedClaims = ClaimRequest::all()
                        ->where('claimEmployee', Auth::user()->id)
                        ->where('claimStatus', 2);

        return view('applyBenefitClaim', ['benefitClaims' => $benefitClaims, 'approvedClaims' => $approvedClaims]);
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
        $claimRequest->claimEmployee = Auth::user()->id;
        $claimRequest->save();

        return redirect()->route('applyBenefitClaim')->with('message', 'Benefit claim applied successfully!');

    }
}
