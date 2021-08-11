<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('changePassword');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'oldPassword' => [new MatchOldPassword],
            'password' => 'required|confirmed|min:8|max:255',
        ]);
        
        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->password)]);

        return redirect()->route("changePassword")->with("message", "Your password has changed successfully");
    }
}
