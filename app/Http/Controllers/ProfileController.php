<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function viewProfile()
    {
        $employees = User::find(Auth::id());

        return view('viewProfile', ['employees' => $employees]);
    }

    public function updateProfileForm()
    {
        $employees = User::find(Auth::id());

        return view('updateProfile', ['employees' => $employees]);
    }
    
    public function updateProfile(Request $request)
    {
        $id = Auth::id();

        if(Auth::user()->isAdmin()){
            $this->validate($request, [
                'firstname' => 'required|max:255',
                'lastname' => 'required|max:255',
                'contactNumber' => 'required|regex:/^(\+6)?01[0-46-9]-[0-9]{7,8}$/|max:14',
                'dateOfBirth' => 'required|before:today',
                'gender' => 'required',
                'email' => 'required|email|max:255|unique:users,email,'.$id.'',
                'username' => 'required|max:255|unique:users,username,'.$id.'',
            ]);
        }
        else{
            $this->validate($request, [
                'firstname' => 'required|max:255',
                'lastname' => 'required|max:255',
                'contactNumber' => 'required|regex:/^(\+6)?01[0-46-9]-[0-9]{7,8}$/|max:14',
                'dateOfBirth' => 'required|before:today',
                'gender' => 'required',
                'email' => 'required|email|max:255|unique:users,email,'.$id.'',
                'username' => 'required|max:255|unique:users,username,'.$id.'',
                'address' => 'required|max:255'
            ]);
        }
        
        $employee = User::findOrFail($id);
        $employee->firstname = $request->firstname;
        $employee->lastname = $request->lastname;
        $employee->contactNumber = $request->contactNumber;
        $employee->dateOfBirth = date("Y-m-d", strtotime($request->dateOfBirth));
        $employee->gender = $request->gender;
        $employee->email = $request->email;
        $employee->username = $request->username;
        if(!Auth::user()->isAdmin()){
            $employee->address = $request->address;
        }
        $employee->save();

       return redirect()->route('viewProfile')->with('message', 'Profile details updated successfully!');
    }
}
