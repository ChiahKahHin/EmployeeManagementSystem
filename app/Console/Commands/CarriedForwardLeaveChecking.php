<?php

namespace App\Console\Commands;

use App\Mail\CFLeaveNotificationMail;
use App\Models\CarriedForwardLeave;
use App\Models\CarriedForwardLeaveRule;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CarriedForwardLeaveChecking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:carriedForwardLeave';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will check all the annual leave that are not applied by the employee';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $rule = CarriedForwardLeaveRule::get()->first();
        if($rule->ableCF == 1){
            //Only carry forward those leave with request
            if($rule->approval == 1){
                $requests = CarriedForwardLeave::whereYear('created_at', date('Y'))->where('status', 2)->get();
                foreach ($requests as $request) {
                    //get original annual leave limit
                    $oriAnnualLeaveLimit = LeaveType::where('leaveType', 'Annual Leave')->first();
                    $leaveLimit = $oriAnnualLeaveLimit->leaveLimit;

                    $approvedAnnualLeave = LeaveRequest::select('leaveDuration')
                                                         ->where('leaveType', 1)
                                                         ->where('leaveStatus', 2)
                                                         ->whereYear('leaveStartDate', date('Y'))
                                                         ->where('employeeID', $request->employeeID)
                                                         ->sum('leaveDuration');

                    if($request->getEmployee->created_at->year == date('Y')){
                        $remainingMonthForThisYear = (12 - $request->getEmployee->created_at->month) + 1;
			            $leaveLimit = ($oriAnnualLeaveLimit->leaveLimit / 12) * $remainingMonthForThisYear;
                    }
                    $actualRemainingLeave = intval($leaveLimit) - $approvedAnnualLeave;
                    if($actualRemainingLeave == 0){
                        $request->leaveLimit = $actualRemainingLeave;
                        $request->save();

                        Mail::to($request->getEmployee->email)->send(new CFLeaveNotificationMail($request));
                    }
                    else{
                        if($actualRemainingLeave > $rule->leaveLimit){
                            $actualRemainingLeave = $rule->leaveLimit;
                        }
                        $request->leaveLimit = $actualRemainingLeave;
                        $request->save();
                        Mail::to($request->getEmployee->email)->send(new CFLeaveNotificationMail($request));
                    }
                }
                
                echo "Carried forward leave with approval is recalculated";
                echo "\n";
            }
            //Convert to annual leave automatically
            else{
                //Clear all carried forward leave requests first
                CarriedForwardLeave::truncate();

                //get original annual leave limit
                $oriAnnualLeaveLimit = LeaveType::where('leaveType', 'Annual Leave')->first();
                $originalAnnualLeaveLimit = $oriAnnualLeaveLimit->leaveLimit;

                //get employee who applied annual leave before
                $annualLeavesEmployees = LeaveRequest::where('leaveType', 1)
                                            ->where('leaveStatus', 2)
                                            ->whereYear('leaveStartDate', date('Y'))
                                            ->distinct()
                                            ->get('employeeID');
                $employeeID = array();
                foreach ($annualLeavesEmployees as $annualLeavesEmployee) {
                    array_push($employeeID, $annualLeavesEmployee->employeeID);
                }

                //update the carried forward leave based on leave request
                for ($i = 0; $i < count($employeeID); $i++) {
                    $annualLeaves = LeaveRequest::where('leaveType', 1)
                                                ->where('leaveStatus', 2)
                                                ->whereYear('leaveStartDate', date('Y'))
                                                ->where('employeeID', $employeeID[$i])
                                                ->get();
                    $totalAppliedAnnualLeave = 0;
                    foreach ($annualLeaves as $annualLeave) {
                        $totalAppliedAnnualLeave += $annualLeave->leaveDuration;
                    }

                    $user = User::find($employeeID[$i]);
                    $leaveLimit = $originalAnnualLeaveLimit;
                    if($user->created_at->year == date('Y')){
                        $remainingMonthForThisYear = (12 - $user->created_at->month) + 1;
                        $leaveLimit = ($originalAnnualLeaveLimit / 12) * $remainingMonthForThisYear;
                    }

                    $actualRemainingLeave = intval($leaveLimit) - $totalAppliedAnnualLeave;

                    if($actualRemainingLeave != 0){
                        if($actualRemainingLeave > $rule->leaveLimit){
                            $actualRemainingLeave = $rule->leaveLimit;    
                        }
                        $carriedForwardLeave = new CarriedForwardLeave();
                        $carriedForwardLeave->employeeID = $employeeID[$i];
                        $carriedForwardLeave->leaveLimit = $actualRemainingLeave;
                        $carriedForwardLeave->useBefore = $rule->useBefore;
                        $carriedForwardLeave->status = 2;
                        $carriedForwardLeave->save();
    
                        Mail::to($carriedForwardLeave->getEmployee->email)->send(new CFLeaveNotificationMail($carriedForwardLeave));
                    }
                    
                }
                
                //get employee who does not apply for annual leave & update the carried forward leave
                $employeeNotAppliedAnnualLeaves = User::whereNotIn('id', $employeeID)->get();
                foreach ($employeeNotAppliedAnnualLeaves as $employeeNotAppliedAnnualLeave) {
                    $user = User::find($employeeNotAppliedAnnualLeave->id);
                    $leaveLimit = $originalAnnualLeaveLimit;
                    if($user->created_at->year == date('Y')){
                        $remainingMonthForThisYear = (12 - $user->created_at->month) + 1;
                        $leaveLimit = ($originalAnnualLeaveLimit / 12) * $remainingMonthForThisYear;
                    }
                    if($leaveLimit > $rule->leaveLimit){
                        $leaveLimit = $rule->leaveLimit;
                    }
                    $carriedForwardLeave = new CarriedForwardLeave();
                    $carriedForwardLeave->employeeID = $employeeNotAppliedAnnualLeave->id;
                    $carriedForwardLeave->leaveLimit = intval($leaveLimit);
                    $carriedForwardLeave->useBefore = $rule->useBefore;
                    $carriedForwardLeave->status = 2;
                    $carriedForwardLeave->save();

                    Mail::to($carriedForwardLeave->getEmployee->email)->send(new CFLeaveNotificationMail($carriedForwardLeave));
                }
                echo "Annual leave converted to carried forward leave automatically";
                echo "\n";
            }
        }
        else{
            CarriedForwardLeave::truncate();
            echo "The annual leave is not able to convert to carried forward leave \n";
            echo "\n";
        }

        if($rule->recurring == 1){
            $useBefore = new Carbon($rule->useBefore);
            $useBefore->addYear(1);
            $rule->useBefore = $useBefore;
            if($rule->approval == 1){
                $startDate = new Carbon($rule->startDate);
                $endDate = new Carbon($rule->endDate);
                $startDate->addYear(1);
                $endDate->addYear(1);
                $rule->startDate = $startDate;
                $rule->endDate = $endDate;
            }
            else{
                $rule->startDate = null;
                $rule->endDate = null;
            }
            $rule->save();
            echo "Carried Forward Leave Configuration Updated Successfully";
            echo "\n";
        }
        else{
            $rule->leaveLimit = null;
            $rule->useBefore = null;
            $rule->approval = null;
            $rule->startDate = null;
            $rule->endDate = null;
            $rule->save();
            echo "Carried Forward Leave Configuration Removed Successfully";
            echo "\n";
        }
    }
}
