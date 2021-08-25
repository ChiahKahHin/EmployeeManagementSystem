<?php

namespace App\Http\Controllers;

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
        return view('addClaimType');
    }

    public function addClaimType(Request $request)
    {
        $this->validate($request, [
            'claimType' => 'required|max:255|unique:claim_types,claimType',
            'claimAmount' => 'required|min:0',
        ]);

        $claimType = new ClaimType();
        $claimType->claimType = $request->claimType;
        $claimType->claimAmount = $request->claimAmount;
        $claimType->save();

        return redirect()->route('addClaimType')->with('message', 'Claim type added successfully!');
    }

    public function manageClaimType()
    {
        $claimTypes = ClaimType::all();

        return view('manageClaimType', ['claimTypes' => $claimTypes]);
    }

    public function editClaimTypeForm($id)
    {
        $claimType = ClaimType::find($id);

        return view('editClaimType', ['claimType' => $claimType]);
    }

    public function editClaimType(Request $request, $id)
    {
        $this->validate($request, [
            'claimType' => 'required|max:255|unique:claim_types,claimType,'.$id.'',
            'claimAmount' => 'required|min:0',
        ]);

        $claimType = ClaimType::find($id);
        $claimType->claimType = $request->claimType;
        $claimType->claimAmount = $request->claimAmount;
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
