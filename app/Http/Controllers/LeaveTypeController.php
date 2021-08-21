<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['employee:admin,hrmanager']);
    }

    public function addLeaveTypeForm()
    {
        return view('addLeaveType');
    }

    public function addLeaveType(Request $request)
    {
        $this->validate($request, [
            'leaveType' => 'required|max:255|unique:leave_types,leaveType',
            'leaveLimit' => 'required|min:1',
            'gender' => 'required',
        ]);

        $leaveType = new LeaveType();
        $leaveType->leaveType = $request->leaveType;
        $leaveType->leaveLimit = $request->leaveLimit;
        $leaveType->gender = $request->gender;
        $leaveType->save();

        return redirect()->route('addLeaveType')->with('message', 'Leave type added successfully!');
    }

    public function manageLeaveType()
    {
        $leaveTypes = LeaveType::all();

        return view('manageLeaveType', ['leaveTypes' => $leaveTypes]);
    }

    public function editLeaveTypeForm($id)
    {
        $leaveType = LeaveType::find($id);
        
        return view('editLeaveType', ['leaveType' => $leaveType]);
    }

    public function editLeaveType(Request $request, $id)
    {
        $this->validate($request, [
            'leaveType' => 'required|max:255|unique:leave_types,leaveType,'.$id.'',
            'leaveLimit' => 'required|min:1',
            'gender' => 'required',
        ]);

        $leaveType = LeaveType::find($id);
        $leaveType->leaveType = $request->leaveType;
        $leaveType->leaveLimit = $request->leaveLimit;
        $leaveType->gender = $request->gender;
        $leaveType->save();

        return redirect()->route('editLeaveType', ['id' => $id])->with('message', 'Leave type details updated successfully!');
    }

    public function deleteLeaveType($id)
    {
        $leaveType = LeaveType::find($id);
        $leaveType->delete();

        return redirect()->route('manageLeaveType');
    }
}
