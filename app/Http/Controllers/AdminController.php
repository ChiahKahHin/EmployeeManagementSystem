<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\EmployeeCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['employee:admin']);
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
            'gender' => 'required',
            'email' => 'required|email|max:255|unique:users,email',
            'username' => 'required|max:255|unique:users,username',
            'password' => 'required|confirmed|min:8|max:255'
        ]);

        $admin = new User();
        $admin->firstname = $request->firstname;
        $admin->lastname = $request->lastname;
        $admin->contactNumber = $request->contactNumber;
        $admin->dateOfBirth = $request->dateOfBirth;
        $admin->gender = $request->gender;
        $admin->email = $request->email;
        $admin->username = $request->username;
        $admin->password = Hash::make($request->password);
        $admin->department = 1;
        $admin->role = 0;
        $admin->save();

        $admin->notify(new EmployeeCreatedNotification($request->firstname, $request->username, $request->password));

        return redirect()->route('addAdmin')->with('message', 'Admin added successfully!')->with('message1', 'An email notification will be sent to the new admin');
    }

    public function manageAdmin()
    {
        $admins = User::all()->except(Auth::id())->where('role', 0);
        
        return view('manageAdmin', ['admins' => $admins]);
    }

    public function editAdminForm($id)
    {
        $admins = User::findOrFail($id);

        return view('editAdmin', ['admins' => $admins]);
    }

    public function editAdmin(Request $request, $id)
    {
        $this->validate($request, [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'contactNumber' => 'required|regex:/^(\+6)?01[0-46-9]-[0-9]{7,8}$/|max:14',
            'dateOfBirth' => 'required|before:today',
            'gender' => 'required',
            'email' => 'required|email|max:255|unique:users,email,'.$id.'',
            'username' => 'required|max:255|unique:users,username,'.$id.''
        ]);
        
        $admin = User::findOrFail($id);
        $admin->firstname = $request->firstname;
        $admin->lastname = $request->lastname;
        $admin->contactNumber = $request->contactNumber;
        $admin->dateOfBirth = $request->dateOfBirth;
        $admin->gender = $request->gender;
        $admin->email = $request->email;
        $admin->username = $request->username;
        $admin->save();

       return redirect()->route('editAdmin', ['id' => $id])->with('message', 'Admin details updated successfully!');
    }

    public function deleteAdmin($id)
    {
        $admin = User::findOrFail($id);
        $admin->delete();

        return redirect()->route('manageAdmin');
    }

    public function viewAdmin($id)
    {
        $admin = User::findOrFail($id);
        
        return view('viewAdmin', ['admin' => $admin]);
    }
}
