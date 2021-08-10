<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function addEmployeeForm()
    {
        $departments = Department::all();

        return view('addEmployee', ['departments' => $departments]);
    }

    public function addEmployee(Request $request)
    {
        $this->validate($request, [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'contactNumber' => 'required|regex:/^(\+6)?01[0-46-9]-[0-9]{7,8}$/|max:14',
            'dateOfBirth' => 'required|before:today',
            'gender' => 'required',
            'address' => 'required|max:255',
            'username' => 'required|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|confirmed|min:8|max:255',
            'department' => 'required',
        ]);

        $employee = new User();
        $employee->firstname = $request->firstname;
        $employee->lastname = $request->lastname;
        $employee->contactNumber = $request->contactNumber;
        $employee->dateOfBirth = date("Y-m-d", strtotime($request->dateOfBirth));
        $employee->gender = $request->gender;
        $employee->address = $request->address;
        $employee->username = $request->username;
        $employee->email = $request->email;
        $employee->password = Hash::make($request->password);
        $employee->department = $request->department;
        if($request->manager == null){
            $employee->role = 3;
        }
        else{
            $employee->role = $request->manager;
        }
        $employee->save();

        return redirect()->route('addEmployee')->with('message', 'Employee added successfully!');
    }
}
