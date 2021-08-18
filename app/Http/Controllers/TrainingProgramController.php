<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\TrainingProgram;
use Illuminate\Http\Request;

class TrainingProgramController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('employee:admin,hrmanager');
    }

    public function addTrainingProgramForm()
    {
        $departments = Department::all()->whereNotIn('departmentName', 'Administration');

        return view('addTrainingProgram', ['departments' =>$departments]);
    }

    public function addTrainingProgram(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'venue' => 'required|max:255',
            'dateAndTime' => 'required',
            'poster' => 'required',
        ]);

        $training_program = new TrainingProgram();
        $training_program->name = $request->name;
        $training_program->description = $request->description;
        $training_program->venue = $request->venue;
        $training_program->dateAndTime = $request->dateAndTime;
        if($request->specificDepartment != null){
            $training_program->department = $request->department;
        }
        
        $training_program->poster = $request->poster;
        $training_program->status = 0;
        $training_program->save();

        return redirect()->route('addTrainingProgram')->with('message', 'Training program created successfully!');
    }
}
