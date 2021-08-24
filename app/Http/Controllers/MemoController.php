<?php

namespace App\Http\Controllers;

use App\Mail\MemoMail;
use App\Models\Department;
use App\Models\Memo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MemoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['employee:admin,hrmanager']);
    }

    public function createMemoForm()
    {
        $departments = Department::all()->whereNotIn('departmentName', 'Administration');

        return view('createMemo', ['departments' => $departments]);
    }

    public function createMemo(Request $request)
    {
        $this->validate($request, [
            'memoTitle' => 'required|max:255',
            'memoDescription' => 'required|max:65535',
        ]);

        $memo = new Memo();
        $memo->memoTitle = $request->memoTitle;
        $memo->memoDescription = $request->memoDescription;
        if($request->specificMemoRecipient != null){
            $memo->memoRecipient = implode(",", $request->memoRecipient);
        }
        else{
            $memo->memoRecipient = 0;
        }

        if ($request->scheduledMemo != null) {
            $memo->memoScheduled = $request->scheduledMemoDateTime;
            $memo->memoDate = date('Y-m-d', strtotime($request->scheduledMemoDateTime));
            $memo->memoStatus = 0;
        }
        else{
            $memo->memoStatus = 1;
            $memo->memoDate = date('Y-m-d');
        }
        $memo->save();

        if($memo->memoStatus == 1){
            $user = new User();

            if($request->specificMemoRecipient == null){
                $emails = $user->getEmployeeEmail();
            }
            else{
                $emails = $user->getEmployeeEmail($request->memoRecipient);
            }
            Mail::to($emails)->send(new MemoMail($memo));
        }

        return redirect()->route('createMemo')->with('message', 'Memo created successfully!');
    }

    public function manageMemo()
    {
        $memos = Memo::orderBy('memoStatus', 'ASC')->orderBy('memoDate', 'DESC')->get();

        return view('manageMemo', ['memos' => $memos]);
    }

    public function editMemoForm($id)
    {
        $memo = Memo::find($id);
        $departments = Department::all()->whereNotIn('departmentName', 'Administration');

        return view('editMemo', ['memo' => $memo, 'departments' => $departments]);
    }

    public function editMemo(Request $request, $id)
    {
        $this->validate($request, [
            'memoTitle' => 'required|max:255',
            'memoDescription' => 'required|max:65535',
        ]);

        $memo = Memo::find($id);
        $memo->memoTitle = $request->memoTitle;
        $memo->memoDescription = $request->memoDescription;
        if($request->specificMemoRecipient != null){
            $memo->memoRecipient = implode(",", $request->memoRecipient);
        }
        else{
            $memo->memoRecipient = 0;
        }

        if ($request->scheduledMemo != null) {
            $memo->memoScheduled = $request->scheduledMemoDateTime;
            $memo->memoDate = date('Y-m-d', strtotime($request->scheduledMemoDateTime));
            $memo->memoStatus = 0;
        }
        else{
            $memo->memoStatus = 1;
            $memo->memoDate = date('Y-m-d');
        }
        $memo->save();

        if($memo->memoStatus == 1){
            $user = new User();

            if($request->specificMemoRecipient == null){
                $emails = $user->getEmployeeEmail();
            }
            else{
                $emails = $user->getEmployeeEmail($request->memoRecipient);
            }
            Mail::to($emails)->send(new MemoMail($memo));
        }
        if($memo->memoStatus == 0){
            return redirect()->route('editMemo', ['id' => $id])->with('message', 'Memo details updated successfully!');
        }
        else{
            return redirect()->route('manageMemo')->with('message', 'Memo sent successfully!');
        }
    }

    public function viewMemo($id)
    {
        $memo = Memo::find($id);

        return view('viewMemo', ['memo' => $memo]);
    }

    public function deleteMemo($id)
    {
        $memo = Memo::find($id);
        $memo->delete();

        return redirect()->route('manageMemo');
    }
}
