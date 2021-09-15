<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\LogoutController;
use App\Http\Controllers\ClaimTypeController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ClaimAnalyticsController;
use App\Http\Controllers\ClaimCategoryController;
use App\Http\Controllers\ClaimRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DelegationController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\LeaveAnalyticsController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\MemoController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicHolidayController;
use App\Http\Controllers\TaskAnalyticsController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TrainingAnalyticsController;
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
Route::get('/dashboard1', [DashboardController::class, 'dashboard1'])->name('dashboard1');
Route::get('/dashboard2', [DashboardController::class, 'dashboard2'])->name('dashboard2');

//Manage Department
Route::get('/addDepartment', [DepartmentController::class, 'addDepartmentForm'])->name('addDepartment');
Route::post('/addDepartment', [DepartmentController::class, 'addDepartment']);
Route::get('/manageDepartment', [DepartmentController::class, 'manageDepartment'])->name('manageDepartment');
Route::get('/editDepartment/{id}', [DepartmentController::class, 'editDepartmentForm'])->name('editDepartment');
Route::post('/editDepartment/{id}', [DepartmentController::class, 'editDepartment']);
Route::get('/deleteDepartment/{id}', [DepartmentController::class, 'deleteDepartment'])->name('deleteDepartment');

//Manage Position
Route::get('/addPosition', [PositionController::class, 'addPositionForm'])->name('addPosition');
Route::post('/addPosition', [PositionController::class, 'addPosition']);
Route::get('/managePosition', [PositionController::class, 'managePosition'])->name('managePosition');
Route::get('/editPosition/{id}', [PositionController::class, 'editPositionForm'])->name('editPosition');
Route::post('/editPosition/{id}', [PositionController::class, 'editPosition']);
Route::get('/deletePosition/{id}', [PositionController::class, 'deletePosition'])->name('deletePosition');

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

//Task Analytics
Route::get('/taskAnalyticsPage', [TaskAnalyticsController::class, 'taskAnalyticsPage'])->name('taskAnalyticsPage');
Route::get('/overallTaskAnalytics/{year}/{department}', [TaskAnalyticsController::class, 'overallTaskAnalytics'])->name('overallTaskAnalytics');
Route::get('/taskCompletedAnalytics/{year}/{department}', [TaskAnalyticsController::class, 'taskCompletedAnalytics'])->name('taskCompletedAnalytics');
Route::get('/taskApprovedAndRejectedAnalytics/{year}/{department}', [TaskAnalyticsController::class, 'taskApprovedAndRejectedAnalytics'])->name('taskApprovedAndRejectedAnalytics');

//Task Analytics (Manager)
Route::get('/taskAnalyticsPage2', [TaskAnalyticsController::class, 'taskAnalyticsPage2'])->name('taskAnalyticsPage2');
Route::get('/overallTaskAnalytics2/{year}/{personInCharge}', [TaskAnalyticsController::class, 'overallTaskAnalytics2'])->name('overallTaskAnalytics2');
Route::get('/taskCompletedAnalytics2/{year}/{personInCharge}', [TaskAnalyticsController::class, 'taskCompletedAnalytics2'])->name('taskCompletedAnalytics2');
Route::get('/taskApprovedAndRejectedAnalytics2/{year}/{personInCharge}', [TaskAnalyticsController::class, 'taskApprovedAndRejectedAnalytics2'])->name('taskApprovedAndRejectedAnalytics2');

//Task Analytics (Employee)
Route::get('/taskAnalyticsPage3', [TaskAnalyticsController::class, 'taskAnalyticsPage3'])->name('taskAnalyticsPage3');
Route::get('/overallTaskAnalytics3/{year}', [TaskAnalyticsController::class, 'overallTaskAnalytics3'])->name('overallTaskAnalytics3');
Route::get('/taskCompletedAnalytics3/{year}', [TaskAnalyticsController::class, 'taskCompletedAnalytics3'])->name('taskCompletedAnalytics3');
Route::get('/taskApprovedAndRejectedAnalytics3/{year}', [TaskAnalyticsController::class, 'taskApprovedAndRejectedAnalytics3'])->name('taskApprovedAndRejectedAnalytics3');

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
Route::get('/cancelClaimRequest/{id}', [ClaimRequestController::class, 'cancelClaimRequest'])->name('cancelClaimRequest');

//Benefit Claim Analytics
Route::get('/claimAnalytics', [ClaimAnalyticsController::class, 'claimAnalytics'])->name('claimAnalytics');
Route::get('/overallClaimAnalytics/{year}/{department}', [ClaimAnalyticsController::class, 'overallClaimAnalytics'])->name('overallClaimAnalytics');
Route::get('/claimApprovedAndRejectedAnalytics/{year}/{department}', [ClaimAnalyticsController::class, 'claimApprovedAndRejectedAnalytics'])->name('claimApprovedAndRejectedAnalytics');

//Benefit Claim Analytics (Manager)
Route::get('/claimAnalytics2', [ClaimAnalyticsController::class, 'claimAnalytics2'])->name('claimAnalytics2');
Route::get('/overallClaimAnalytics2/{year}/{personInCharge}', [ClaimAnalyticsController::class, 'overallClaimAnalytics2'])->name('overallClaimAnalytics2');
Route::get('/claimApprovedAndRejectedAnalytics2/{year}/{personInCharge}', [ClaimAnalyticsController::class, 'claimApprovedAndRejectedAnalytics2'])->name('claimApprovedAndRejectedAnalytics2');

//Benefit Claim Analytics (Employee)
Route::get('/claimAnalytics3', [ClaimAnalyticsController::class, 'claimAnalytics3'])->name('claimAnalytics3');
Route::get('/overallClaimAnalytics3/{year}', [ClaimAnalyticsController::class, 'overallClaimAnalytics3'])->name('overallClaimAnalytics3');
Route::get('/claimApprovedAndRejectedAnalytics3/{year}', [ClaimAnalyticsController::class, 'claimApprovedAndRejectedAnalytics3'])->name('claimApprovedAndRejectedAnalytics3');

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

//Training Analytics
Route::get('/trainingAnalytics', [TrainingAnalyticsController::class, 'trainingAnalytics'])->name('trainingAnaytics');
Route::get('/trainingAddedAnalytics/{year}', [TrainingAnalyticsController::class, 'trainingAddedAnalytics'])->name('trainingAddedAnalytics');
Route::get('/trainingAnalytics2', [TrainingAnalyticsController::class, 'trainingAnalytics2'])->name('trainingAnaytics2');
Route::get('/trainingRegisteredAnalytics/{year}', [TrainingAnalyticsController::class, 'trainingRegisteredAnalytics'])->name('trainingRegisteredAnalytics');

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
Route::get('/cancelLeaveRequest/{id}', [LeaveRequestController::class, 'cancelLeaveRequest'])->name('cancelLeaveRequest');

//Leave Analytics
Route::get('/leaveAnalytics', [LeaveAnalyticsController::class, 'leaveAnalytics'])->name('leaveAnalytics');
Route::get('/overallLeaveAnalytics/{year}/{department}', [LeaveAnalyticsController::class, 'overallLeaveAnalytics'])->name('overallLeaveAnalytics');
Route::get('/leaveApprovedAndRejectedAnalytics/{year}/{department}', [LeaveAnalyticsController::class, 'leaveApprovedAndRejectedAnalytics'])->name('leaveApprovedAndRejectedAnalytics');

//Leave Analytics (Manager)
Route::get('/leaveAnalytics2', [LeaveAnalyticsController::class, 'leaveAnalytics2'])->name('leaveAnalytics2');
Route::get('/overallLeaveAnalytics2/{year}/{personInCharge}', [LeaveAnalyticsController::class, 'overallLeaveAnalytics2'])->name('overallLeaveAnalytics2');
Route::get('/leaveApprovedAndRejectedAnalytics2/{year}/{personInCharge}', [LeaveAnalyticsController::class, 'leaveApprovedAndRejectedAnalytics2'])->name('leaveApprovedAndRejectedAnalytics2');

//Leave Analytics (Employee)
Route::get('/leaveAnalytics3', [LeaveAnalyticsController::class, 'leaveAnalytics3'])->name('leaveAnalytics3');
Route::get('/overallLeaveAnalytics3/{year}', [LeaveAnalyticsController::class, 'overallLeaveAnalytics3'])->name('overallLeaveAnalytics3');
Route::get('/leaveApprovedAndRejectedAnalytics3/{year}', [LeaveAnalyticsController::class, 'leaveApprovedAndRejectedAnalytics3'])->name('leaveApprovedAndRejectedAnalytics3');

//Approval Delegation
Route::get('/addDelegation', [DelegationController::class, 'addDelegationForm'])->name('addDelegation');
Route::post('/addDelegation', [DelegationController::class, 'addDelegation']);
Route::get('/manageDelegation', [DelegationController::class, 'manageDelegation'])->name('manageDelegation');
Route::get('/cancelDelegation/{id}', [DelegationController::class, 'cancelDelegation'])->name('cancelDelegation');
Route::get('/viewDelegation/{id}', [DelegationController::class, 'viewDelegation'])->name('viewDelegation');
Route::get('/deleteDelegation/{id}', [DelegationController::class, 'deleteDelegation'])->name('deleteDelegation');