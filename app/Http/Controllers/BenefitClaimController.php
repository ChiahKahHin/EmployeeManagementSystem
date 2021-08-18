<?php

namespace App\Http\Controllers;

use App\Models\BenefitClaim;
use Illuminate\Http\Request;

class BenefitClaimController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['employee:Admin,hrManager']);
    }

    public function addBenefitClaimForm()
    {
        return view('addBenefitClaim');
    }

    public function addBenefitClaim(Request $request)
    {
        $this->validate($request, [
            'claimType' => 'required|max:255|unique:benefit_claims,claimType',
            'claimAmount' => 'required|min:0',
        ]);

        $benefitClaim = new BenefitClaim();
        $benefitClaim->claimType = $request->claimType;
        $benefitClaim->claimAmount = $request->claimAmount;
        $benefitClaim->save();

        return redirect()->route('addBenefitClaim')->with('message', 'Benefit claim added successfully!');
    }

    public function manageBenefitClaim()
    {
        $benefitClaims = BenefitClaim::all();

        return view('manageBenefitClaim', ['benefitClaims' => $benefitClaims]);
    }

    public function editBenefitClaimForm($id)
    {
        $benefitClaim = BenefitClaim::find($id);

        return view('editBenefitClaim', ['benefitClaim' => $benefitClaim]);
    }

    public function editBenefitClaim(Request $request, $id)
    {
        $this->validate($request, [
            'claimType' => 'required|max:255|unique:benefit_claims,claimType,'.$id.'',
            'claimAmount' => 'required|min:0',
        ]);

        $benefitClaim = BenefitClaim::find($id);
        $benefitClaim->claimType = $request->claimType;
        $benefitClaim->claimAmount = $request->claimAmount;
        $benefitClaim->save();

        return redirect()->route('editBenefitClaim', ['id' => $id])->with('message', 'Benefit claim details updated successfully!');
    }

    public function deleteBenefitClaim($id)
    {
        $benefitClaim = BenefitClaim::find($id);
        $benefitClaim->delete();

        return redirect()->route('manageBenefitClaim');
    }
}
