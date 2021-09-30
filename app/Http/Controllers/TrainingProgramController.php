<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\TrainingAttendee;
use App\Models\TrainingProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingProgramController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('employee:admin,hrmanager')->only(['addTrainingProgramForm', 'addTrainingProgram', 'editTrainingProgramForm', 'editTrainingProgram', 'deleteTrainingProgram']);
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
        
        $training_program->poster = file_get_contents($request->poster);
        $training_program->status = 0;
        $training_program->save();

        return redirect()->route('addTrainingProgram')->with('message', 'Training program created successfully!');
    }

    public function manageTrainingProgram()
    {
        if(Auth::user()->isAdmin() || Auth::user()->isHrManager()){
            $trainingPrograms = TrainingProgram::with('getAttendees')->orderBy('status', 'ASC')->orderBy('dateAndTime', 'ASC')->get();
        }
        else{
            $trainingPrograms = TrainingProgram::with('getAttendees')->orderBy('status', 'ASC')->orderBy('dateAndTime', 'ASC')
                                                ->where('department', Auth::user()->department)
                                                ->orWhereNull('department')->get();
        }

        return view('manageTrainingProgram', ['trainingPrograms' => $trainingPrograms]);
    }

    public function viewTrainingProgram($id)
    {
        $training_program = TrainingProgram::find($id);
        $trainingAttendees = TrainingAttendee::with('getEmployee.getEmployeeInfo')->where('trainingProgram', $id)->get();

        return view('viewTrainingProgram', ['trainingProgram' => $training_program, 'trainingAttendees' => $trainingAttendees]);
    }

    public function editTrainingProgramForm($id)
    {
        $training_program = TrainingProgram::find($id);
        $departments = Department::all()->whereNotIn('departmentName', 'Administration');

        return view('editTrainingProgram', ['trainingProgram' => $training_program, 'departments' => $departments]);
    }

    public function editTrainingProgram(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'venue' => 'required|max:255',
            'dateAndTime' => 'required',
        ]);

        $training_program = TrainingProgram::find($id);
        $training_program->name = $request->name;
        $training_program->description = $request->description;
        $training_program->venue = $request->venue;
        $training_program->dateAndTime = $request->dateAndTime;
        if($request->specificDepartment != null){
            $training_program->department = $request->department;
        }
        else{
            $training_program->department = null;
        }
        
        if($request->poster != null){
            $training_program->poster = file_get_contents($request->poster);
        }
        $training_program->save();

        return redirect()->route('editTrainingProgram', ['id' => $id])->with('message', 'Training program details updated successfully!');
    }

    public function deleteTrainingProgram($id)
    {
        $trainingAttendees = TrainingAttendee::where('trainingProgram', $id)->delete();

        $training_program = TrainingProgram::find($id);
        $training_program->delete();

        return redirect()->route('manageTrainingProgram');
    }

    public function viewTrainingProgram2($id)
    {
        $training_program = TrainingProgram::find($id);

        return view('viewTrainingProgram2', ['trainingProgram' => $training_program]);
    }

    public function registerTrainingProgram($id)
    {
        $trainingAttendee = new TrainingAttendee();
        $trainingAttendee->trainingProgram = $id;
        $trainingAttendee->employeeID = Auth::id();
        $trainingAttendee->save();

        return redirect()->route('viewTrainingProgram2', ['id' => $id]);
    }

    public function cancelTrainingProgram($id)
    {
        $trainingAttendee = TrainingAttendee::where('trainingProgram', $id)->where('employeeID', Auth::id())->first();
        $trainingAttendee->delete();

        return redirect()->route('viewTrainingProgram2', ['id' => $id]);
    }
}
