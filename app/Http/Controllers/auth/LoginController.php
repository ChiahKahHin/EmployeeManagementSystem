<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware(['guest']);
    }

    public function index()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "username" => "required|max:255",
            "password" => "required"
        ]);

        if(auth()->attempt($request->only('username', 'password'), $request->remember)) {
            if(Auth::user()->isAdmin()){
                return redirect()->route("adminDashboard");
            }
            elseif(Auth::user()->isHrManager()){
                return redirect()->route("hrManagerDashboard");
            }
            elseif(Auth::user()->isManager()){
                return redirect()->route("managerDashboard");
            }
            elseif(Auth::user()->isEmployee()){
                return redirect()->route("employeeDashboard");
            }
        }
        else{
            return redirect()->route("login")->withInput()->with("message", "Invalid login credentials");
        }
    }
}
