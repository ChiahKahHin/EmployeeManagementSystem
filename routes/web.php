<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\LogoutController;
use App\Http\Controllers\ClaimTypeController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ClaimCategoryController;
use App\Http\Controllers\ClaimRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\MemoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicHolidayController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TrainingProgramController;
use App\Http\Controllers\WorkingDayController;
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
Route::get('/deleteDepartment/{id}', [DepartmentController::class, 'deleteDepartment'])->name('deleteDepartment');

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
Route::get('/editTask/{id}', [TaskController::class, 'editTaskForm'])->name('editTask');
Route::post('/editTask/{id}', [TaskController::class, 'editTask']);
Route::get('/deleteTask/{id}', [TaskController::class, 'deleteTask'])->name('deleteTask');
Route::get('/viewTask/{id}', [TaskController::class, 'viewTask'])->name('viewTask');
Route::get('/approveTask/{id}', [TaskController::class, 'approveTask'])->name('approveTask');
Route::get('/rejectTask/{id}/{reason}', [TaskController::class, 'rejectTask'])->name('rejectTask');
Route::get('/completeTask/{id}', [TaskController::class, 'completeTask'])->name('completeTask');
Route::post('/changeTaskManager/{id}', [TaskController::class, 'changeTaskManager'])->name('changeTaskManager');

//Manage Claim Category
Route::get('/addClaimCategory', [ClaimCategoryController::class, 'addClaimCategoryForm'])->name('addClaimCategory');
Route::post('/addClaimCategory', [ClaimCategoryController::class, 'addClaimCategory']);
Route::get('/manageClaimCategory', [ClaimCategoryController::class, 'manageClaimCategory'])->name('manageClaimCategory');
Route::get('/editClaimCategory/{id}', [ClaimCategoryController::class, 'editClaimCategoryForm'])->name('editClaimCategory');
Route::post('/editClaimCategory/{id}', [ClaimCategoryController::class, 'editClaimCategory']);
Route::get('/deleteClaimCategory/{id}', [ClaimCategoryController::class, 'deleteClaimCategory'])->name('deleteClaimCategory');

//Manage Claim Type
Route::get('/addClaimType', [ClaimTypeController::class, 'addClaimTypeForm'])->name('addClaimType');
Route::post('/addClaimType', [ClaimTypeController::class, 'addClaimType']);
Route::get('/manageClaimType', [ClaimTypeController::class, 'manageClaimType'])->name('manageClaimType');
Route::get('/editClaimType/{id}', [ClaimTypeController::class, 'editClaimTypeForm'])->name('editClaimType');
Route::post('/editClaimType/{id}', [ClaimTypeController::class, 'editClaimType']);
Route::get('/deleteClaimType/{id}', [ClaimTypeController::class, 'deleteClaimType'])->name('deleteClaimType');

//Benefit Claim Application
Route::get('/applyBenefitClaim', [ClaimRequestController::class, 'applyBenefitClaimForm'])->name('applyBenefitClaim');
Route::post('/applyBenefitClaim', [ClaimRequestController::class, 'applyBenefitClaim']);
Route::get('/manageClaimRequest', [ClaimRequestController::class, 'manageClaimRequest'])->name('manageClaimRequest');
Route::get('/viewClaimRequest/{id}', [ClaimRequestController::class, 'viewClaimRequest'])->name('viewClaimRequest');
Route::get('/deleteClaimRequest/{id}', [ClaimRequestController::class, 'deleteClaimRequest'])->name('deleteClaimRequest');
Route::get('/approveClaimRequest/{id}', [ClaimRequestController::class, 'approveClaimRequest'])->name('approveClaimRequest');
Route::get('/rejectClaimRequest/{id}/{reason}', [ClaimRequestController::class, 'rejectClaimRequest'])->name('rejectClaimRequest');

//Memorandum
Route::get('/createMemo', [MemoController::class, 'createMemoForm'])->name('createMemo');
Route::post('/createMemo', [MemoController::class, 'createMemo']);
Route::get('/manageMemo', [MemoController::class, 'manageMemo'])->name('manageMemo');
Route::get('/editMemo/{id}', [MemoController::class, 'editMemoForm'])->name('editMemo');
Route::post('/editMemo/{id}', [MemoController::class, 'editMemo']);
Route::get('/viewMemo/{id}', [MemoController::class, 'viewMemo'])->name('viewMemo');
Route::get('/deleteMemo/{id}', [MemoController::class, 'deleteMemo'])->name('deleteMemo');

//Training Program
Route::get('/addTrainingProgram', [TrainingProgramController::class, 'addTrainingProgramForm'])->name('addTrainingProgram');
Route::post('/addTrainingProgram', [TrainingProgramController::class, 'addTrainingProgram']);
Route::get('/manageTrainingProgram', [TrainingProgramController::class, 'manageTrainingProgram'])->name('manageTrainingProgram');
Route::get('/viewTrainingProgram/{id}', [TrainingProgramController::class, 'viewTrainingProgram'])->name('viewTrainingProgram');
Route::get('/editTrainingProgram/{id}', [TrainingProgramController::class, 'editTrainingProgramForm'])->name('editTrainingProgram');
Route::post('/editTrainingProgram/{id}', [TrainingProgramController::class, 'editTrainingProgram']);
Route::get('/deleteTrainingProgram/{id}', [TrainingProgramController::class, 'deleteTrainingProgram'])->name('deleteTrainingProgram');
Route::get('/viewTrainingProgram2/{id}', [TrainingProgramController::class, 'viewTrainingProgram2'])->name('viewTrainingProgram2');
Route::get('/registerTrainingProgram/{id}', [TrainingProgramController::class, 'registerTrainingProgram'])->name('registerTrainingProgram');
Route::get('/cancelTrainingProgram/{id}', [TrainingProgramController::class, 'cancelTrainingProgram'])->name('cancelTrainingProgram');

//Working Day
Route::get('/manageWorkingDay', [WorkingDayController::class, 'manageWorkingDayForm'])->name('manageWorkingDay');
Route::post('/manageWorkingDay', [WorkingDayController::class, 'manageWorkingDay']);

//Public Holiday
Route::get('/addPublicHoliday', [PublicHolidayController::class, 'addPublicHolidayForm'])->name('addPublicHoliday');
Route::post('/addPublicHoliday', [PublicHolidayController::class, 'addPublicHoliday']);
Route::get('/managePublicHoliday', [PublicHolidayController::class, 'managePublicHoliday'])->name('managePublicHoliday');
Route::get('/editPublicHoliday/{id}', [PublicHolidayController::class, 'editPublicHolidayForm'])->name('editPublicHoliday');
Route::post('/editPublicHoliday/{id}', [PublicHolidayController::class, 'editPublicHoliday']);
Route::get('/deletePublicHoliday/{id}', [PublicHolidayController::class, 'deletePublicHoliday'])->name('deletePublicHoliday');

//Leave Type
Route::get('/addLeaveType', [LeaveTypeController::class, 'addLeaveTypeForm'])->name('addLeaveType');
Route::post('/addLeaveType', [LeaveTypeController::class, 'addLeaveType']);
Route::get('/manageLeaveType', [LeaveTypeController::class, 'manageLeaveType'])->name('manageLeaveType');
Route::get('/editLeaveType/{id}', [LeaveTypeController::class, 'editLeaveTypeForm'])->name('editLeaveType');
Route::post('/editLeaveType/{id}', [LeaveTypeController::class, 'editLeaveType']);
Route::get('/deleteLeaveType/{id}', [LeaveTypeController::class, 'deleteLeaveType'])->name('deleteLeaveType');

//Leave Request
Route::get('/leaveCalendar', [LeaveRequestController::class, 'leaveCalendar'])->name('leaveCalendar');
Route::get('/applyLeave', [LeaveRequestController::class, 'applyLeaveForm'])->name('applyLeave');
Route::post('/applyLeave', [LeaveRequestController::class, 'applyLeave']);
Route::get('/manageLeave', [LeaveRequestController::class, 'manageLeave'])->name('manageLeave');
Route::get('/viewLeave/{id}', [LeaveRequestController::class, 'viewLeave'])->name('viewLeave');
Route::get('/approveLeaveRequest/{id}', [LeaveRequestController::class, 'approveLeaveRequest'])->name('approveLeaveRequest');
Route::get('/rejectLeaveRequest/{id}/{reason}', [LeaveRequestController::class, 'rejectLeaveRequest'])->name('rejectLeaveRequest');
Route::get('/deleteLeave/{id}', [LeaveRequestController::class, 'deleteLeave'])->name('deleteLeave');