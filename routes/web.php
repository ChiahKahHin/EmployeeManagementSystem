<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\LogoutController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Login
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/', [LoginController::class, 'store']);

//Logout
Route::get('/logout', [LogoutController::class, 'index'])->name('logout');

//Forget Password
Route::get('/forgetPassword', [ForgetPasswordController::class, 'index'])->name('forgetPassword');
Route::post('/forgetPassword', [ForgetPasswordController::class, 'notifyEmail']);
Route::get('/forgetPassword/{token}', [ForgetPasswordController::class, 'resetPassword'])->name('password.reset');
Route::post('/forgetPassword/{token}', [ForgetPasswordController::class, 'changePassword']);

//Change Password
Route::get('/changePassword', [ChangePasswordController::class, 'index'])->name('changePassword');
Route::post('/changePassword', [ChangePasswordController::class, 'store']);

//View & Update Profile
Route::get('/viewProfile', [ProfileController::class, 'viewProfile'])->name('viewProfile');
Route::get('/updateProfile', [ProfileController::class, 'updateProfileForm'])->name('updateProfile');
Route::post('/updateProfile', [ProfileController::class, 'updateProfile']);

//Dashboard
Route::get('/adminDashboard', [DashboardController::class, 'adminDashboard'])->name('adminDashboard');
Route::get('/hrManagerDashboard', [DashboardController::class, 'hrManagerDashboard'])->name('hrManagerDashboard');
Route::get('/managerDashboard', [DashboardController::class, 'managerDashboard'])->name('managerDashboard');
Route::get('/employeeDashboard', [DashboardController::class, 'employeeDashboard'])->name('employeeDashboard');

//Manage Admin
Route::get('/addAdmin', [AdminController::class, 'addAdminForm'])->name('addAdmin');
Route::post('/addAdmin', [AdminController::class, 'addAdmin']);
Route::get('/manageAdmin', [AdminController::class, 'manageAdmin'])->name('manageAdmin');
Route::get('/editAdmin/{id}', [AdminController::class, 'editAdminForm'])->name('editAdmin');
Route::post('/editAdmin/{id}', [AdminController::class, 'editAdmin']);
Route::get('/deleteAdmin/{id}', [AdminController::class, 'deleteAdmin'])->name('deleteAdmin');
Route::get('/viewAdmin/{id}', [AdminController::class, 'viewAdmin'])->name('viewAdmin');

//Manage Department
Route::get('/addDepartment', [DepartmentController::class, 'addDepartmentForm'])->name('addDepartment');
Route::post('/addDepartment', [DepartmentController::class, 'addDepartment']);
Route::get('/manageDepartment', [DepartmentController::class, 'manageDepartment'])->name('manageDepartment');
Route::get('/editDepartment/{id}', [DepartmentController::class, 'editDepartmentForm'])->name('editDepartment');
Route::post('/editDepartment/{id}', [DepartmentController::class, 'editDepartment']);
Route::get('/deleteDepartment/{id}', [DepartmentController::class, 'deleteDepartment'])->name('deleteAdmin');

//Manage Employee
Route::get('/addEmployee', [EmployeeController::class, 'addEmployeeForm'])->name('addEmployee');
Route::post('/addEmployee', [EmployeeController::class, 'addEmployee']);
Route::get('/manageEmployee', [EmployeeController::class, 'manageEmployee'])->name('manageEmployee');
Route::get('/editEmployee/{id}', [EmployeeController::class, 'editEmployeeForm'])->name('editEmployee');
Route::post('/editEmployee/{id}', [EmployeeController::class, 'editEmployee']);
Route::get('/deleteEmployee/{id}', [EmployeeController::class, 'deleteEmployee'])->name('deleteEmployee');
Route::get('/viewEmployee/{id}', [EmployeeController::class, 'viewEmployee'])->name('viewEmployee');

//Manage Task
Route::get('/addTask', [TaskController::class, 'addTaskForm'])->name('addTask');
Route::post('/addTask', [TaskController::class, 'addTask']);
Route::get('/manageTask', [TaskController::class, 'manageTask'])->name('manageTask');
Route::get('/deleteTask/{id}', [TaskController::class, 'deleteTask'])->name('deleteTask');
Route::get('/viewTask/{id}', [TaskController::class, 'viewTask'])->name('viewTask');
Route::get('/approveTask/{id}', [TaskController::class, 'approveTask'])->name('approveTask');
Route::get('/rejectTask/{id}/{reason}', [TaskController::class, 'rejectTask'])->name('rejectTask');