<?php

namespace App\Http\Controllers;

use App\Models\TrainingAttendee;
use App\Models\TrainingProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingAnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['employee:admin'])->only(['trainingAnalytics', 'trainingAddedAnalytics']);
        $this->middleware(['employee:hrmanager,manager,employee'])->only(['trainingAnalytics2', 'trainingRegisteredAnalytics']);
    }

    public function trainingAnalytics()
    {
        $trainingAddedYears = array();
        $trainingPrograms = TrainingProgram::select('updated_at')->where('status', 1)->get();
        foreach ($trainingPrograms as $trainingProgram) {
            if(!in_array($trainingProgram->updated_at->year, $trainingAddedYears)){
                array_push($trainingAddedYears, $trainingProgram->updated_at->year);
            }
        }
        rsort($trainingAddedYears);

        return view('trainingAnalytics', ['trainingAddedYears' => $trainingAddedYears]);
    }

    public function trainingAddedAnalytics($year)
    {
        $trainingAddedArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        
        $addedTrainings = TrainingProgram::where('updated_at', 'like', ''.$year.'%')->where('status', 1)->get();
        foreach ($addedTrainings as $addedtraining) {
            $month = date('m', strtotime($addedtraining->updated_at));
            $trainingAddedArrays[$month] = $trainingAddedArrays[$month] + 1;
        }        

        return array_values($trainingAddedArrays);
    }

    public function trainingAnalytics2()
    {
        $trainingRegistered = array();
        $trainingRegisteredYears = array();

        $attendeesLists = TrainingAttendee::where('employeeID', Auth::id())->get();
        foreach ($attendeesLists as $attendeesList) {
            if(!in_array($attendeesList->trainingProgram, $trainingRegistered)){
                array_push($trainingRegistered, $attendeesList->trainingProgram);
            }
        }

        $trainingPrograms = TrainingProgram::select('updated_at')->whereIn('id', $trainingRegistered)->where('status', 1)->get();
        foreach ($trainingPrograms as $trainingProgram) {
            if(!in_array($trainingProgram->updated_at->year, $trainingRegisteredYears)){
                array_push($trainingRegisteredYears, $trainingProgram->updated_at->year);
            }
        }
        rsort($trainingRegisteredYears);

        return view('trainingAnalytics2', ['trainingRegisteredYears' => $trainingRegisteredYears]);
    }

    public function trainingRegisteredAnalytics($year)
    {
        $trainingRegisteredArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        
        $trainingRegistered = array();

        $attendeesLists = TrainingAttendee::where('employeeID', Auth::id())->get();
        foreach ($attendeesLists as $attendeesList) {
            if(!in_array($attendeesList->trainingProgram, $trainingRegistered)){
                array_push($trainingRegistered, $attendeesList->trainingProgram);
            }
        }

        $addedTrainings = TrainingProgram::where('updated_at', 'like', ''.$year.'%')->whereIn('id', $trainingRegistered)->where('status', 1)->get();
        foreach ($addedTrainings as $addedtraining) {
            $month = date('m', strtotime($addedtraining->updated_at));
            $trainingRegisteredArrays[$month] = $trainingRegisteredArrays[$month] + 1;
        }        

        return array_values($trainingRegisteredArrays);
    }
}
