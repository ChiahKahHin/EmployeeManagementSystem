<?php

namespace App\Http\Controllers;

use App\Models\ClaimCategory;
use App\Models\ClaimType;
use Illuminate\Http\Request;

class ClaimTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['employee:Admin,hrManager']);
    }

    public function addClaimTypeForm()
    {
        $claimCategories = ClaimCategory::all();

        return view('addClaimType', ['claimCategories' => $claimCategories]);
    }

    public function addClaimType(Request $request)
    {
        $this->validate($request, [
            'claimCategory' => 'required',
            'claimType' => 'required|max:255|unique:claim_types,claimType',
            'claimAmount' => 'required|min:0',
            'claimPeriod' => 'required',
        ]);

        $claimType = new ClaimType();
        $claimType->claimCategory = $request->claimCategory;
        $claimType->claimType = $request->claimType;
        $claimType->claimAmount = $request->claimAmount;
        $claimType->claimPeriod = $request->claimPeriod;
        $claimType->save();

        return redirect()->route('addClaimType')->with('message', 'Claim type added successfully!');
    }

    public function manageClaimType()
    {
        $claimTypes = ClaimType::with('getClaimCategory')->get();

        return view('manageClaimType', ['claimTypes' => $claimTypes]);
    }

    public function editClaimTypeForm($id)
    {
        $claimType = ClaimType::find($id);
        $claimCategories = ClaimCategory::all();

        return view('editClaimType', ['claimType' => $claimType, 'claimCategories' => $claimCategories]);
    }

    public function editClaimType(Request $request, $id)
    {
        $this->validate($request, [
            'claimCategory' => 'required',
            'claimType' => 'required|max:255|unique:claim_types,claimType,'.$id.'',
            'claimAmount' => 'required|min:0',
            'claimPeriod' => 'required',
        ]);

        $claimType = ClaimType::find($id);
        $claimType->claimCategory = $request->claimCategory;
        $claimType->claimType = $request->claimType;
        $claimType->claimAmount = $request->claimAmount;
        $claimType->claimPeriod = $request->claimPeriod;
        $claimType->save();

        return redirect()->route('editClaimType', ['id' => $id])->with('message', 'Claim type details updated successfully!');
    }

    public function deleteClaimType($id)
    {
        $claimType = ClaimType::find($id);
        $claimType->delete();

        return redirect()->route('manageClaimType');
    }
}
