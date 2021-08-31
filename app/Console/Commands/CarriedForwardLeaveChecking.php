<?php

namespace App\Console\Commands;

use App\Mail\CarriedForwardLeaveMail;
use App\Models\CarriedForwardLeave;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
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
        CarriedForwardLeave::truncate();

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
                $carriedForwardLeave = new CarriedForwardLeave();
                $carriedForwardLeave->employeeID = $employeeID[$i];
                $carriedForwardLeave->leaveLimit = $originalAnnualLeaveLimit - $totalAppliedAnnualLeave;
                $carriedForwardLeave->save();

                Mail::to($carriedForwardLeave->getEmployee->email)->send(new CarriedForwardLeaveMail($carriedForwardLeave));
            }
        }
        
        //get employee who does not apply for annual leave & update the carried forward leave
        $employeeNotAppliedAnnualLeaves = User::whereNotIn('id', $employeeID)->whereIn('role', [1,2,3])->get();
        foreach ($employeeNotAppliedAnnualLeaves as $employeeNotAppliedAnnualLeave) {
            $carriedForwardLeave = new CarriedForwardLeave();
            $carriedForwardLeave->employeeID = $employeeNotAppliedAnnualLeave->id;
            $carriedForwardLeave->leaveLimit = $originalAnnualLeaveLimit;
            $carriedForwardLeave->save();

            Mail::to($carriedForwardLeave->getEmployee->email)->send(new CarriedForwardLeaveMail($carriedForwardLeave));
        }
        echo "Carried Forward Leave Checking done";
    }
}
