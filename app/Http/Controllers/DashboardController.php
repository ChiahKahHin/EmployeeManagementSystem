<?php

namespace App\Http\Controllers;
use App\Middleware\Admin;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['admin'])->only(['adminDashboard']);
        $this->middleware(['hrManager'])->only(['hrManagerDashboard']);
    }

    public function adminDashboard()
    {
        return view('adminDashboard');
    }

    public function hrManagerDashboard()
    {
        return view('hrManagerDashboard');
    }
}
