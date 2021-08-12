<?php

namespace App\Http\Controllers;

use App\Mail\TaskAssignedMail;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('managerAndHrManager');
    }

    public function addTaskForm()
    {
        $personInCharges = User::all()->except(Auth::id())->where('department', Auth::user()->department);

        return view('addTask', ['personInCharges' => $personInCharges]);
    }

    public function addTask(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'personInCharge' => 'required',
            'priority' => 'required',
            'dueDate' => 'required|after:today',
        ]);

        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->personInCharge = $request->personInCharge;
        $task->priority = $request->priority;
        $task->dueDate = date("Y-m-d", strtotime($request->dueDate));
        $task->status = 0;
        $task->save();

        $employee = User::find($task->personInCharge);
        
        Mail::to($employee->email)->send(new TaskAssignedMail($employee, $task));

        return redirect()->route('addTask')->with('message', 'Task added successfully!');
    }
}
