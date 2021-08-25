<?php

namespace App\Http\Controllers;

use App\Models\ClaimCategory;
use Illuminate\Http\Request;

class ClaimCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['employee:Admin,hrManager']);
    }

    public function addClaimCategoryForm()
    {
        return view('addClaimCategory');
    }

    public function addClaimCategory(Request $request)
    {
        $this->validate($request, [
            'claimCategory' => 'required|max:255|unique:claim_categories,claimCategory',
        ]);

        $claimCategory = new ClaimCategory();
        $claimCategory->claimCategory = $request->claimCategory;
        $claimCategory->save();

        return redirect()->route('addClaimCategory')->with('message', 'Claim category added successfully!');
    }

    public function manageClaimCategory()
    {
        $claimCategories = ClaimCategory::all();

        return view('manageClaimCategory', ['claimCategories' => $claimCategories]);
    }

    public function editClaimCategoryForm($id)
    {
        $claimCategory = ClaimCategory::find($id);

        return view('editClaimCategory', ['claimCategory' => $claimCategory]);
    }

    public function editClaimCategory(Request $request, $id)
    {
        $this->validate($request, [
            'claimCategory' => 'required|max:255|unique:claim_categories,claimCategory,'.$id.'',
        ]);

        $claimCategory = ClaimCategory::find($id);
        $claimCategory->claimCategory = $request->claimCategory;
        $claimCategory->save();

        return redirect()->route('editClaimCategory', ['id' => $id])->with('message', 'Claim category details updated successfully!');
    }

    public function deleteClaimCategory($id)
    {
        $claimCategory = ClaimCategory::find($id);
        $claimCategory->delete();

        return redirect()->route('manageClaimCategory');
    }
}
