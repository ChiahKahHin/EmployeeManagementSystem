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
        $this->middleware(['manager'])->only(['managerDashboard']);
        $this->middleware(['employee'])->only(['employeeDashboard']);
    }

    public function adminDashboard()
    {
        return view('adminDashboard');
    }

    public function hrManagerDashboard()
    {
        return view('hrManagerDashboard');
    }

    public function managerDashboard()
    {
        return view('managerDashboard');
    }

    public function employeeDashboard()
    {
        return view('employeeDashboard');
    }
}
