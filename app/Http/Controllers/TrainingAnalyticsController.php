<?php

namespace App\Http\Controllers;

use App\Models\TrainingProgram;
use Illuminate\Http\Request;

class TrainingAnalyticsController extends Controller
{
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
}
