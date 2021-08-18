<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['employee:admin,hrmanager']);
    }

    public function addDepartmentForm()
    {
        return view('addDepartment');
    }

    public function addDepartment(Request $request)
    {
        $this->validate($request, [
            'departmentCode' => 'required|max:255|unique:departments,departmentCode',
            'departmentName' => 'required|max:255|unique:departments,departmentName'
        ]);

        $department = new Department();
        $department->departmentCode = $request->departmentCode;
        $department->departmentName = $request->departmentName;
        $department->save();

        return redirect()->route('addDepartment')->with('message', 'Department added successfully!');
    }

    public function manageDepartment()
    {
        $departments = Department::all();
        
        return view('manageDepartment', ['departments' => $departments]);
    }

    public function editDepartmentForm($id)
    {
        $departments = Department::findOrFail($id);

        return view('editDepartment', ['departments' => $departments]);
    }

    public function editDepartment(Request $request, $id)
    {
        $this->validate($request, [
            'departmentCode' => 'required|max:255|unique:departments,departmentCode,'.$id.'',
            'departmentName' => 'required|max:255|unique:departments,departmentName'.$id.''
        ]);
        
        $department = Department::findOrFail($id);
        $department->departmentCode = $request->departmentCode;
        $department->departmentName = $request->departmentName;
        $department->save();

       return redirect()->route('editDepartment', ['id' => $id])->with('message', 'Department details updated successfully!');
    }

    public function deleteDepartment($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return redirect()->route('manageDepartment');
    }
}
