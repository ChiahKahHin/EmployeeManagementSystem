<?php

namespace App\Http\Controllers;
use App\Middleware\Admin;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        return view('adminDashboard');
    }
}
