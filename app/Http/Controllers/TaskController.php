<?php

namespace App\Http\Controllers;

use App\Mail\TaskApprovedOrRejectedMail;
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
        $this->middleware('managerAndHrManager')->only(['addTaskForm', 'addTask']);
    }

    public function addTaskForm()
    {
        $personInCharges = User::all()->except(Auth::id())
                            ->where('department', Auth::user()->department)
                            ->where('role', 3);

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
        $task->department = Auth::user()->department;
        $task->manager = Auth::user()->id;
        $task->priority = $request->priority;
        $task->dueDate = date("Y-m-d", strtotime($request->dueDate));
        $task->status = 0;
        $task->save();

        $email = $task->getEmail($task->personInCharge);
        
        Mail::to($email)->send(new TaskAssignedMail($task));

        return redirect()->route('addTask')->with('message', 'Task added successfully!');
    }

    public function manageTask()
    {
        if(Auth::user()->isAdmin()){
            $tasks = Task::all();
        }
        elseif(Auth::user()->isEmployee()){
            $tasks = Task::all()->where('department', Auth::user()->department)->where('personInCharge', Auth::user()->id);
        }
        else{
            $tasks = Task::all()->where('department', Auth::user()->department);
        }

        return view('manageTask', ['tasks' => $tasks]);
    }

    public function deleteTask($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return redirect()->route('manageTask');
    }

    public function viewTask($id)
    {
        $task = Task::findOrFail($id);

        return view('viewTask', ['task' => $task]);
    }

    public function approveTask($id)
    {
        $task = Task::find($id);
        $task->status = 3;
        $task->save();

        $email = $task->getEmail($task->personInCharge);

        Mail::to($email)->send(new TaskApprovedOrRejectedMail($task));

        return redirect()->route('viewTask', ['id' => $id]);
    }

    public function rejectTask($id, $reason)
    {
        $task = Task::find($id);
        $task->status = 2;
        $task->save();

        $email = $task->getEmail($task->personInCharge);

        Mail::to($email)->send(new TaskApprovedOrRejectedMail($task, $reason));

        return redirect()->route('viewTask', ['id' => $id]);
    }
}
