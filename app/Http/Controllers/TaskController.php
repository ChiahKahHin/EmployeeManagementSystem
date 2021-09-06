<?php

namespace App\Http\Controllers;

use App\Mail\TaskNotificationMail;
use App\Models\RejectedTask;
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
        $this->middleware(['employee:hrmanager,manager'])->only(['addTaskForm', 'addTask']);
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

        if(is_array($request->personInCharge)){
            for ($i=0; $i < count($request->personInCharge); $i++) { 
                $task = new Task();
                $task->title = $request->title;
                $task->description = $request->description;
                $task->personInCharge = $request->personInCharge[$i];
                $task->department = Auth::user()->department;
                $task->manager = $task->getReportingManager($request->personInCharge[$i]);
                $task->priority = $request->priority;
                $task->dueDate = $request->dueDate;
                $task->status = 0;
                $task->save();
                
                $email = $task->getEmail($task->personInCharge);
                
                Mail::to($email)->send(new TaskNotificationMail($task));
            }
        }
        else{
            $task = new Task();
            $task->title = $request->title;
            $task->description = $request->description;
            $task->personInCharge = $request->personInCharge;
            $task->department = Auth::user()->department;
            $task->manager = $task->getReportingManager($request->personInCharge);
            $task->priority = $request->priority;
            $task->dueDate = $request->dueDate;
            $task->status = 0;
            $task->save();
    
            $email = $task->getEmail($task->personInCharge);
            
            Mail::to($email)->send(new TaskNotificationMail($task));

        }

        return redirect()->route('addTask')->with('message', 'Task added successfully!');
    }

    public function manageTask()
    {
        if(Auth::user()->isAdmin()){
            $tasks = Task::with('getPersonInCharge')->orderBy('status', 'ASC')->orderBy('dueDate', 'ASC')->get();
        }
        elseif(Auth::user()->isEmployee()){
            $tasks = Task::with('getManager')->orderBy('status', 'ASC')->orderBy('dueDate', 'ASC')->where('personInCharge', Auth::user()->id)->get();
        }
        else{
            $tasks = Task::with('getPersonInCharge')->orderBy('status', 'ASC')->orderBy('dueDate', 'ASC')->where('manager', Auth::user()->id)->get();
        }

        return view('manageTask', ['tasks' => $tasks]);
    }

    public function editTaskForm($id)
    {
        $task = Task::findOrFail($id);
        $personInCharges = User::all()->except(Auth::id())
                            ->where('department', Auth::user()->department)
                            ->where('role', 3);
        
        return view('editTask', ['task' => $task, 'personInCharges' => $personInCharges]);
    }

    public function editTask(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'personInCharge' => 'required',
            'priority' => 'required',
            'dueDate' => 'required|after:today',
        ]);

        $task = Task::find($id);
        $task->title = $request->title;
        $task->description = $request->description;
        $task->personInCharge = $request->personInCharge;
        $task->priority = $request->priority;
        $task->dueDate = $request->dueDate;
        $task->save();        

        return redirect()->route('editTask', ['id' => $task->id])->with('message', 'Task details updated successfully!');
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
        if ((Auth::user()->isEmployee() && $task->personInCharge != Auth::id()) || (Auth::user()->isManager() && $task->manager != Auth::id()) || (Auth::user()->isHrManager() && $task->manager != Auth::id())) {
            return redirect()->route('manageTask');
        }
        $managers = User::with('getDepartment')->orderBy('role', 'DESC')->whereIn('role', [1,2])->get();

        return view('viewTask', ['task' => $task, 'managers' => $managers]);
    }

    public function approveTask($id)
    {
        $task = Task::find($id);
        $task->status = 3;
        $task->save();

        $email = $task->getEmail($task->personInCharge);

        Mail::to($email)->send(new TaskNotificationMail($task));

        return redirect()->route('viewTask', ['id' => $id]);
    }

    public function rejectTask($id, $reason)
    {
        $task = Task::find($id);
        $task->status = 2;
        $task->save();

        $rejectedTask = new RejectedTask();
        $rejectedTask->taskID = $id;
        $rejectedTask->rejectedReason = $reason;
        $rejectedTask->save();

        $email = $task->getEmail($task->personInCharge);

        Mail::to($email)->send(new TaskNotificationMail($task, $reason));

        return redirect()->route('viewTask', ['id' => $id]);
    }

    public function completeTask($id)
    {
        $task = Task::find($id);
        $task->status = 1;
        $task->save();

        $email = $task->getEmail($task->manager);

        Mail::to($email)->send(new TaskNotificationMail($task));

        return redirect()->route('viewTask', ['id' => $id]);
    }

    public function changeTaskManager(Request $request, $id)
    {
        $task = Task::find($id);
        $task->manager = $request->manager;
        $task->save();

        $emails = array();
        array_push($emails, $task->getEmail($task->manager));
        array_push($emails, $task->getEmail($task->personInCharge));

        Mail::to($emails)->send(new TaskNotificationMail($task, null, true));

        return redirect()->route('manageTask')->with('message', 'Task approval manager delegate successfully!');
    }

    public function taskAnalyticsPage()
    {
        $taskAddedYears = array();
        $tasks = Task::select('created_at')->get();
        foreach ($tasks as $task) {
            if(!in_array($task->created_at->year, $taskAddedYears)){
                array_push($taskAddedYears, $task->created_at->year);
            }
        }
        rsort($taskAddedYears);

        $taskApprovedAndRejectedYears = array();
        $tasks = Task::select('created_at')->whereIn('status', [2, 3])->get();
        foreach ($tasks as $task) {
            if(!in_array($task->created_at->year, $taskApprovedAndRejectedYears)){
                array_push($taskApprovedAndRejectedYears, $task->created_at->year);
            }
        }
        rsort($taskApprovedAndRejectedYears);

        return view('taskAnalytics', ['taskAddedYears' => $taskAddedYears, 'taskApprovedAndRejectedYears' => $taskApprovedAndRejectedYears]);
    }

    public function taskAddedAnalytics($year)
    {
        $taskAddedArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);

        $tasks = Task::where('created_at', 'like', ''.$year.'%')->get();
        foreach ($tasks as $task) {
            $month = date('m', strtotime($task->created_at));
            $taskAddedArrays[$month] = $taskAddedArrays[$month] + 1;
        }

        return array_values($taskAddedArrays);
    }

    public function taskApprovedAndRejectedAnalytics($year)
    {
        $taskApprovedArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        $taskRejectedArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);

        $approvedTasks = Task::where('created_at', 'like', ''.$year.'%')->where('status', 3)->get();
        foreach ($approvedTasks as $approvedTask) {
            $month = date('m', strtotime($approvedTask->created_at));
            $taskApprovedArrays[$month] = $taskApprovedArrays[$month] + 1;
        }

        $rejectedTasks = Task::where('created_at', 'like', ''.$year.'%')->where('status', 2)->get();
        foreach ($rejectedTasks as $rejectedTask) {
            $month = date('m', strtotime($rejectedTask->created_at));
            $taskRejectedArrays[$month] = $taskRejectedArrays[$month] + 1;
        }

        return [array_values($taskApprovedArrays), array_values($taskRejectedArrays)];
    }

    public function overallTaskAnalytics($year)
    {  
        $taskStatus = array();
        $taskLabel = array();
        $taskNumber = array();

        $statuses = Task::select('status')->distinct()->where('created_at', 'like', ''.$year.'%')->orderBy('status', 'ASC')->get();
        foreach ($statuses as $status) {
            array_push($taskStatus, $status->status);
            array_push($taskLabel, $status->getTaskStatus());
        }
        
        for ($i=0; $i < count($taskStatus); $i++) { 
            $tasks = Task::where('status', $taskStatus[$i])->where('created_at', 'like', ''.$year.'%')->count();
            array_push($taskNumber, $tasks);
        }
        return [array_values($taskLabel), array_values($taskNumber)];
    }
}
