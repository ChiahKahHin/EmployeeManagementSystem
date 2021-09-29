<?php

namespace App\Console\Commands;

use App\Mail\CarriedForwardLeaveMail;
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
        //clear all existing carried forward leave
        /*CarriedForwardLeave::truncate();

        //get original annual leave limit
        $oriAnnualLeaveLimit = LeaveType::where('leaveType', 'Annual Leave')->first();
        $originalAnnualLeaveLimit = $oriAnnualLeaveLimit->leaveLimit;

        //get employee who applied annual leave before
        $annualLeavesEmployees = LeaveRequest::where('leaveType', 1)
                                      ->where('leaveStatus', 2)
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
                                          ->where('employeeID', $employeeID[$i])
                                          ->get();
            $totalAppliedAnnualLeave = 0;
            foreach ($annualLeaves as $annualLeave) {
                if(date("Y", strtotime($annualLeave->leaveStartDate)) == date("Y")){
                    $totalAppliedAnnualLeave += $annualLeave->leaveDuration;
                }
            }
            if($totalAppliedAnnualLeave < $originalAnnualLeaveLimit){
                $user = User::find($employeeID[$i]);
                $averageAnnualLeaveLimit = 0;
                if($user->created_at->year == date('Y')){
                    $remainingMonthForThisYear = (12 - $user->created_at->month) + 1;
                    $averageAnnualLeaveLimit = ($originalAnnualLeaveLimit / 12) * $remainingMonthForThisYear;
                }
                $carriedForwardLeave = new CarriedForwardLeave();
                $carriedForwardLeave->employeeID = $employeeID[$i];
                if($averageAnnualLeaveLimit == 0){
                    $carriedForwardLeave->leaveLimit = $originalAnnualLeaveLimit - $totalAppliedAnnualLeave;
                }
                else{
                    $carriedForwardLeave->leaveLimit = intval($averageAnnualLeaveLimit) - $totalAppliedAnnualLeave;
                }
                $carriedForwardLeave->save();

                Mail::to($carriedForwardLeave->getEmployee->email)->send(new CarriedForwardLeaveMail($carriedForwardLeave));
            }
        }
        
        //get employee who does not apply for annual leave & update the carried forward leave
        $employeeNotAppliedAnnualLeaves = User::whereNotIn('id', $employeeID)->get();
        foreach ($employeeNotAppliedAnnualLeaves as $employeeNotAppliedAnnualLeave) {
            $user = User::find($employeeNotAppliedAnnualLeave->id);
            $averageAnnualLeaveLimit = 0;
            if($user->created_at->year == date('Y')){
                $remainingMonthForThisYear = (12 - $user->created_at->month) + 1;
                $averageAnnualLeaveLimit = ($originalAnnualLeaveLimit / 12) * $remainingMonthForThisYear;
            }
            $carriedForwardLeave = new CarriedForwardLeave();
            $carriedForwardLeave->employeeID = $employeeNotAppliedAnnualLeave->id;
            if($averageAnnualLeaveLimit == 0){
                $carriedForwardLeave->leaveLimit = $originalAnnualLeaveLimit;
            }
            else{
                $carriedForwardLeave->leaveLimit = intval($averageAnnualLeaveLimit); 
            }
            $carriedForwardLeave->save();

            Mail::to($carriedForwardLeave->getEmployee->email)->send(new CarriedForwardLeaveMail($carriedForwardLeave));
        }*/

        $rule = CarriedForwardLeaveRule::get()->first();
        $useBefore = new Carbon($rule->useBefore);
        $startDate = new Carbon($rule->startDate);
        $endDate = new Carbon($rule->endDate);
        echo $useBefore->addYear(1);
        echo "\n";
        echo $startDate->addYear(1);
        echo "\n";
        echo $endDate->addYear(1);
        echo "\n";
        $rule->useBefore = $useBefore;
        $rule->startDate = $startDate;
        $rule->endDate = $endDate;
        $rule->save();
        echo "Carried Forward Leave Configuration Updated Successfully";
    }
}
