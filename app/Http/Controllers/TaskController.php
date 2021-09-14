<?php

namespace App\Http\Controllers;

use App\Mail\TaskNotificationMail;
use App\Models\RejectedTask;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
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
        $personInCharges = User::where('reportingManager', Auth::id())
                                ->where('role', 3)
                                ->get();

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
                $task->managerID = Auth::id();
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
            $task->managerID = Auth::id();
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
            $tasks = Task::with('getPersonInCharge')
                          ->orderBy('status', 'ASC')
                          ->orderBy('dueDate', 'ASC')
                          ->where('managerID', Auth::user()->id)
                          ->orWhere('delegateManagerID', Auth::user()->id)
                          ->get();
        }

        return view('manageTask', ['tasks' => $tasks]);
    }

    public function editTaskForm($id)
    {
        $task = Task::findOrFail($id);
        $personInCharges = User::where('reportingManager', Auth::id())
                                ->where('role', 3)
                                ->get();
        
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

        if(Auth::user()->isAdmin() || Auth::id() == $task->personInCharge || Auth::id() == $task->managerID || Auth::id() == $task->delegateManagerID){
            return view('viewTask', ['task' => $task]);
        }
        
        return redirect()->route('manageTask');
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
        if($task->getPersonInCharge->delegateManager != null){
            $task->delegateManagerID = $task->getPersonInCharge->delegateManager;
            $task->save();
            $email = $task->getEmail($task->delegateManagerID);
        }
        else{
            $email = $task->getEmail($task->managerID);
        }

        Mail::to($email)->send(new TaskNotificationMail($task));

        return redirect()->route('viewTask', ['id' => $id]);
    }

    public function taskAnalyticsPage()
    {
        $overallTaskYears = array();
        $tasks = Task::select('updated_at')->get();
        foreach ($tasks as $task) {
            if(!in_array($task->updated_at->year, $overallTaskYears)){
                array_push($overallTaskYears, $task->updated_at->year);
            }
        }
        rsort($overallTaskYears);

        $departments = Task::with('getPersonInCharge.getDepartment')->select('personInCharge', 'status')->distinct()->get();

        $taskCompletedYears = array();
        $tasks = Task::select('updated_at')->where('status', 3)->get();
        foreach ($tasks as $task) {
            if(!in_array($task->updated_at->year, $taskCompletedYears)){
                array_push($taskCompletedYears, $task->updated_at->year);
            }
        }
        rsort($taskCompletedYears);

        $taskApprovedAndRejectedYears = array();
        $tasks = Task::select('updated_at')->whereIn('status', [2, 3])->get();
        foreach ($tasks as $task) {
            if(!in_array($task->updated_at->year, $taskApprovedAndRejectedYears)){
                array_push($taskApprovedAndRejectedYears, $task->updated_at->year);
            }
        }
        rsort($taskApprovedAndRejectedYears);

        return view('taskAnalytics', [
                                        'overallTaskYears' => $overallTaskYears, 
                                        'taskCompletedYears' => $taskCompletedYears,
                                        'taskApprovedAndRejectedYears' => $taskApprovedAndRejectedYears,
                                        'departments' => $departments,
                                    ]);
    }

    public function overallTaskAnalytics($year, $department)
    {  
        $taskStatus = array();
        $taskLabel = array();
        $taskNumber = array();

        if($department == "null"){
            $statuses = Task::select('status')->distinct()->where('updated_at', 'like', ''.$year.'%')->orderBy('status', 'ASC')->get();
            foreach ($statuses as $status) {
                array_push($taskStatus, $status->status);
                array_push($taskLabel, $status->getTaskStatus());
            }
            
            for ($i=0; $i < count($taskStatus); $i++) { 
                $tasks = Task::where('status', $taskStatus[$i])->where('updated_at', 'like', ''.$year.'%')->count();
                array_push($taskNumber, $tasks);
            }
        }
        else{
            $statuses = Task::select('tasks.status')->distinct()
                             ->where('tasks.updated_at', 'like', ''.$year.'%')
                             ->join('users', function ($join) use ($department){
                                $join->on('tasks.personInCharge', 'users.id')
                                     ->where('users.department', $department);
                             })
                             ->orderBy('tasks.status', 'ASC')->get();
            foreach ($statuses as $status) {
                array_push($taskStatus, $status->status);
                array_push($taskLabel, $status->getTaskStatus());
            }
            
            for ($i=0; $i < count($taskStatus); $i++) { 
                $tasks = Task::where('tasks.status', $taskStatus[$i])
                              ->where('tasks.updated_at', 'like', ''.$year.'%')
                              ->join('users', function ($join) use ($department){
                                $join->on('tasks.personInCharge', 'users.id')
                                     ->where('users.department', $department);
                             })
                              ->count();
                array_push($taskNumber, $tasks);
            }
        }

        return [array_values($taskLabel), array_values($taskNumber)];
    }

    public function taskCompletedAnalytics($year, $department)
    {
        $taskCompletedBeforeDueDateArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        $taskCompletedAfterDueDateArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);

        if($department == "null"){
            $tasks = Task::where('updated_at', 'like', ''.$year.'%')->where('status', 3)->get();
            foreach ($tasks as $task) {
                $month = date('m', strtotime($task->updated_at));
                if($task->updated_at->format('Y-m-d') <= $task->dueDate){
                    $taskCompletedBeforeDueDateArrays[$month] = $taskCompletedBeforeDueDateArrays[$month] + 1;
                }
                else{
                    $taskCompletedAfterDueDateArrays[$month] = $taskCompletedAfterDueDateArrays[$month] + 1;
                }
            }
        }
        else{
            $tasks = Task::select('tasks.updated_at as taskUpdatedAt', 'tasks.dueDate')
                           ->where('tasks.updated_at', 'like', ''.$year.'%')
                           ->where('tasks.status', 3)
                           ->join('users', function ($join) use ($department) {
                               $join->on('tasks.personInCharge', 'users.id')
                                    ->select('users.departmet')
                                    ->where('users.department', $department);
                           })->get();
            foreach ($tasks as $task) {
                $updated_at = new Carbon($task->taskUpdatedAt);
                $month = date('m', strtotime($updated_at));
                if($updated_at->format('Y-m-d') <= $task->dueDate){
                    $taskCompletedBeforeDueDateArrays[$month] = $taskCompletedBeforeDueDateArrays[$month] + 1;
                }
                else{
                    $taskCompletedAfterDueDateArrays[$month] = $taskCompletedAfterDueDateArrays[$month] + 1;
                }
            }
        }

        return [array_values($taskCompletedBeforeDueDateArrays), array_values($taskCompletedAfterDueDateArrays)];
    }

    public function taskApprovedAndRejectedAnalytics($year, $department)
    {
        $taskApprovedArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        $taskRejectedArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        if($department == "null"){
            $approvedTasks = Task::where('updated_at', 'like', ''.$year.'%')->where('status', 3)->get();
            foreach ($approvedTasks as $approvedTask) {
                $month = date('m', strtotime($approvedTask->updated_at));
                $taskApprovedArrays[$month] = $taskApprovedArrays[$month] + 1;
            }
    
            $rejectedTasks = Task::where('updated_at', 'like', ''.$year.'%')->where('status', 2)->get();
            foreach ($rejectedTasks as $rejectedTask) {
                $month = date('m', strtotime($rejectedTask->updated_at));
                $taskRejectedArrays[$month] = $taskRejectedArrays[$month] + 1;
            }
        }
        else{
            $approvedTasks = Task::select('tasks.updated_at as taskUpdatedAt')
                                   ->where('tasks.updated_at', 'like', ''.$year.'%')
                                   ->where('tasks.status', 3)
                                   ->join('users', function ($join) use ($department) {
                                        $join->on('tasks.personInCharge', 'users.id')
                                            ->select('users.departmet')
                                            ->where('users.department', $department);
                                    })->get();
            foreach ($approvedTasks as $approvedTask) {
                $updated_at = new Carbon($approvedTask->taskUpdatedAt);
                $month = date('m', strtotime($updated_at));
                $taskApprovedArrays[$month] = $taskApprovedArrays[$month] + 1;
            }
    
            $rejectedTasks = Task::select('tasks.updated_at as taskUpdatedAt')
                                    ->where('tasks.updated_at', 'like', ''.$year.'%')
                                    ->where('tasks.status', 2)
                                    ->join('users', function ($join) use ($department) {
                                        $join->on('tasks.personInCharge', 'users.id')
                                            ->select('users.departmet')
                                            ->where('users.department', $department);
                                    })->get();
            foreach ($rejectedTasks as $rejectedTask) {
                $updated_at = new Carbon($rejectedTask->taskUpdatedAt);
                $month = date('m', strtotime($updated_at));
                $taskRejectedArrays[$month] = $taskRejectedArrays[$month] + 1;
            }
        }

        return [array_values($taskApprovedArrays), array_values($taskRejectedArrays)];
    }
}
