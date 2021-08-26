<?php

namespace App\Http\Controllers;

use App\Models\WorkingDay;
use Illuminate\Http\Request;

class WorkingDayController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['employee:Admin,hrManager']);
    }

    public function manageWorkingDayForm()
    {
        $workingDays = WorkingDay::all();

        return view('manageWorkingDay', ['workingDays' => $workingDays]);
    }

    public function manageWorkingDay(Request $request)
    {
        if($request->workingDay == null){
            return redirect()->route('manageWorkingDay')->with('error', 'At least one working day is needed');
        }
        $allWorkingDay = array(1,2,3,4,5,6,7);
        $diffWorkingDays = array_diff($allWorkingDay, $request->workingDay);
        $workingDays = $request->workingDay;

        foreach($workingDays as $workingDay){
            $workingDay = WorkingDay::find($workingDay);
            $workingDay->status = 1;
            $workingDay->save();
        }

        if(count($diffWorkingDays) != 0){
            foreach($diffWorkingDays as $diffWorkingDay){
                $workingDay = WorkingDay::find($diffWorkingDay);
                $workingDay->status = 0;
                $workingDay->save();
            }
        }

        return redirect()->route('manageWorkingDay')->with('message', 'Working day updated successfully!');
    }
}
