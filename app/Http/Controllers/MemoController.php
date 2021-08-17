<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Memo;
use Illuminate\Http\Request;

class MemoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function createMemoForm()
    {
        $departments = Department::all();
        return view('createMemo', ['departments' => $departments]);
    }

    public function createMemo(Request $request)
    {
        $this->validate($request, [
            'memoTitle' => 'required|max:255',
            'memoDescription' => 'required|max:65535',
            'memoDate' => 'required|date',
            'memoRecipient' => 'required',
        ]);

        $memo = new Memo();
        $memo->memoTitle = $request->memoTitle;
        $memo->memoDescription = $request->memoDescription;
        $memo->memoDate = $request->memoDate;
        $memo->memoRecipient = $request->memoRecipient;

        if ($request->scheduledMemo != null) {
            $memo->memoScheduled = $request->scheduledMemoDateTime;
            $memo->memoStatus = 0;
        }
        else{
            $memo->memoStatus = 1;
        }
        $memo->save();

        return redirect()->route('createMemo')->with('message', 'Memo created successfully!');
    }
}
