<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function addAdminForm()
    {
        return view('addAdmin');
    }

    public function addAdmin(Request $request)
    {
        $this->validate($request, [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'contactNumber' => 'required|regex:/^(\+6)?01[0-46-9]-[0-9]{7,8}$/|max:14',
            'dateOfBirth' => 'required|before:today',
            'email' => 'required|email|max:255|unique:users,email',
            'username' => 'required|max:255|unique:users,username',
            'password' => 'required|confirmed|min:8|max:255'
        ]);

        $admin = new User();
        $admin->firstname = $request->firstname;
        $admin->lastname = $request->lastname;
        $admin->contactNumber = $request->contactNumber;
        $admin->dateOfBirth = date("Y-m-d", strtotime($request->dateOfBirth));
        $admin->email = $request->email;
        $admin->username = $request->username;
        $admin->password = Hash::make($request->password);
        $admin->role = 0;
        $admin->save();
        return redirect()->route('addAdmin')->with('message', 'Admin added successfully!');
    }

    public function manageAdmin()
    {
        $admins = User::all()->except(Auth::id())->where('role', 0);
        
        return view('manageAdmin', ['admins' => $admins]);
    }
}
