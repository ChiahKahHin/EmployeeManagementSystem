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
            $mailRedirect = redirect()->intended()->getTargetUrl();
            if($mailRedirect != "http://localhost:8000"){
                return redirect($mailRedirect);
            }
            else{
                if(Auth::user()->isAdmin() || Auth::user()->isHrManager()){
                    return redirect()->route("dashboard1");
                }
                elseif(Auth::user()->isManager() || Auth::user()->isEmployee()){
                    return redirect()->route("dashboard2");
                }
                else{
                    return redirect()->route("login");
                }
            }
        }
        else{
            return redirect()->route("login")->withInput()->with("message", "Invalid login credentials");
        }
    }
}
