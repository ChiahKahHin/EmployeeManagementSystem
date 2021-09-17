<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskAnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['employee:admin'])->only(['taskAnalyticsPage', 'overallTaskAnalytics', 'taskCompletedAnalytics', 'taskApprovedAndRejectedAnalytics']);
        $this->middleware(['employee:hrmanager,manager'])->only(['taskAnalyticsPage2', 'overallTaskAnalytics2', 'taskCompletedAnalytics2', 'taskApprovedAndRejectedAnalytics2']);
        //$this->middleware(['employee:employee'])->only(['taskAnalyticsPage3', 'overallTaskAnalytics3', 'taskCompletedAnalytics3', 'taskApprovedAndRejectedAnalytics3']);
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

    public function taskAnalyticsPage2()
    {
        $overallTaskYears = array();
        $tasks = Task::select('updated_at')->where('managerID', Auth::id())->get();
        foreach ($tasks as $task) {
            if(!in_array($task->updated_at->year, $overallTaskYears)){
                array_push($overallTaskYears, $task->updated_at->year);
            }
        }
        rsort($overallTaskYears);

        $personInCharges = Task::with('getPersonInCharge')->select('personInCharge', 'status')->where('managerID', Auth::id())->distinct()->get();

        $taskCompletedYears = array();
        $tasks = Task::select('updated_at')->where('status', 3)->where('managerID', Auth::id())->get();
        foreach ($tasks as $task) {
            if(!in_array($task->updated_at->year, $taskCompletedYears)){
                array_push($taskCompletedYears, $task->updated_at->year);
            }
        }
        rsort($taskCompletedYears);

        $taskApprovedAndRejectedYears = array();
        $tasks = Task::select('updated_at')->whereIn('status', [2, 3])->where('managerID', Auth::id())->get();
        foreach ($tasks as $task) {
            if(!in_array($task->updated_at->year, $taskApprovedAndRejectedYears)){
                array_push($taskApprovedAndRejectedYears, $task->updated_at->year);
            }
        }
        rsort($taskApprovedAndRejectedYears);

        return view('taskAnalytics2', [
                                        'overallTaskYears' => $overallTaskYears, 
                                        'taskCompletedYears' => $taskCompletedYears,
                                        'taskApprovedAndRejectedYears' => $taskApprovedAndRejectedYears,
                                        'personInCharges' => $personInCharges,
                                    ]);
    }

    public function overallTaskAnalytics2($year, $personInCharge)
    {  
        $taskStatus = array();
        $taskLabel = array();
        $taskNumber = array();

        if($personInCharge == "null"){
            $statuses = Task::select('status')->distinct()->where('updated_at', 'like', ''.$year.'%')->where('managerID', Auth::id())->orderBy('status', 'ASC')->get();
            foreach ($statuses as $status) {
                array_push($taskStatus, $status->status);
                array_push($taskLabel, $status->getTaskStatus());
            }
            
            for ($i=0; $i < count($taskStatus); $i++) { 
                $tasks = Task::where('status', $taskStatus[$i])->where('updated_at', 'like', ''.$year.'%')->where('managerID', Auth::id())->count();
                array_push($taskNumber, $tasks);
            }
        }
        else{
            $statuses = Task::select('status')->distinct()
                             ->where('updated_at', 'like', ''.$year.'%')
                             ->where('managerID', Auth::id())
                             ->where('personInCharge', $personInCharge)
                             ->orderBy('status', 'ASC')->get();
            foreach ($statuses as $status) {
                array_push($taskStatus, $status->status);
                array_push($taskLabel, $status->getTaskStatus());
            }
            
            for ($i=0; $i < count($taskStatus); $i++) { 
                $tasks = Task::where('status', $taskStatus[$i])
                              ->where('updated_at', 'like', ''.$year.'%')
                              ->where('managerID', Auth::id())
                              ->where('personInCharge', $personInCharge)
                              ->count();
                array_push($taskNumber, $tasks);
            }
        }

        return [array_values($taskLabel), array_values($taskNumber)];
    }

    public function taskCompletedAnalytics2($year, $personInCharge)
    {
        $taskCompletedBeforeDueDateArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        $taskCompletedAfterDueDateArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);

        if($personInCharge == "null"){
            $tasks = Task::where('updated_at', 'like', ''.$year.'%')->where('status', 3)->where('managerID', Auth::id())->get();
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
            $tasks = Task::select('updated_at', 'dueDate')
                           ->where('updated_at', 'like', ''.$year.'%')
                           ->where('status', 3)
                           ->where('managerID', Auth::id())
                           ->where('personInCharge', $personInCharge)
                           ->get();

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

        return [array_values($taskCompletedBeforeDueDateArrays), array_values($taskCompletedAfterDueDateArrays)];
    }

    public function taskApprovedAndRejectedAnalytics2($year, $personInCharge)
    {
        $taskApprovedArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        $taskRejectedArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        if($personInCharge == "null"){
            $approvedTasks = Task::where('updated_at', 'like', ''.$year.'%')->where('status', 3)->where('managerID', Auth::id())->get();
            foreach ($approvedTasks as $approvedTask) {
                $month = date('m', strtotime($approvedTask->updated_at));
                $taskApprovedArrays[$month] = $taskApprovedArrays[$month] + 1;
            }
    
            $rejectedTasks = Task::where('updated_at', 'like', ''.$year.'%')->where('status', 2)->where('managerID', Auth::id())->get();
            foreach ($rejectedTasks as $rejectedTask) {
                $month = date('m', strtotime($rejectedTask->updated_at));
                $taskRejectedArrays[$month] = $taskRejectedArrays[$month] + 1;
            }
        }
        else{
            $approvedTasks = Task::select('updated_at')
                                   ->where('updated_at', 'like', ''.$year.'%')
                                   ->where('status', 3)
                                   ->where('managerID', Auth::id())
                                   ->where('personInCharge', $personInCharge)
                                   ->get();
            foreach ($approvedTasks as $approvedTask) {
                $month = date('m', strtotime($approvedTask->updated_at));
                $taskApprovedArrays[$month] = $taskApprovedArrays[$month] + 1;
            }
    
            $rejectedTasks = Task::select('updated_at')
                                    ->where('updated_at', 'like', ''.$year.'%')
                                    ->where('status', 2)
                                    ->where('managerID', Auth::id())
                                    ->where('personInCharge', $personInCharge)
                                    ->get();
            foreach ($rejectedTasks as $rejectedTask) {
                $month = date('m', strtotime($rejectedTask->updated_at));
                $taskRejectedArrays[$month] = $taskRejectedArrays[$month] + 1;
            }
        }

        return [array_values($taskApprovedArrays), array_values($taskRejectedArrays)];
    }

    public function taskAnalyticsPage3()
    {
        $overallTaskYears = array();
        $tasks = Task::select('updated_at')->where('personInCharge', Auth::id())->get();
        foreach ($tasks as $task) {
            if(!in_array($task->updated_at->year, $overallTaskYears)){
                array_push($overallTaskYears, $task->updated_at->year);
            }
        }
        rsort($overallTaskYears);

        $taskCompletedYears = array();
        $tasks = Task::select('updated_at')->where('status', 3)->where('personInCharge', Auth::id())->get();
        foreach ($tasks as $task) {
            if(!in_array($task->updated_at->year, $taskCompletedYears)){
                array_push($taskCompletedYears, $task->updated_at->year);
            }
        }
        rsort($taskCompletedYears);

        $taskApprovedAndRejectedYears = array();
        $tasks = Task::select('updated_at')->whereIn('status', [2, 3])->where('personInCharge', Auth::id())->get();
        foreach ($tasks as $task) {
            if(!in_array($task->updated_at->year, $taskApprovedAndRejectedYears)){
                array_push($taskApprovedAndRejectedYears, $task->updated_at->year);
            }
        }
        rsort($taskApprovedAndRejectedYears);

        return view('taskAnalytics3', [
                                        'overallTaskYears' => $overallTaskYears, 
                                        'taskCompletedYears' => $taskCompletedYears,
                                        'taskApprovedAndRejectedYears' => $taskApprovedAndRejectedYears,
                                    ]);
    }

    public function overallTaskAnalytics3($year)
    {  
        $taskStatus = array();
        $taskLabel = array();
        $taskNumber = array();

        $statuses = Task::select('status')->distinct()->where('updated_at', 'like', ''.$year.'%')->where('personInCharge', Auth::id())->orderBy('status', 'ASC')->get();
        foreach ($statuses as $status) {
            array_push($taskStatus, $status->status);
            array_push($taskLabel, $status->getTaskStatus());
        }
        
        for ($i=0; $i < count($taskStatus); $i++) { 
            $tasks = Task::where('status', $taskStatus[$i])->where('updated_at', 'like', ''.$year.'%')->where('personInCharge', Auth::id())->count();
            array_push($taskNumber, $tasks);
        }

        return [array_values($taskLabel), array_values($taskNumber)];
    }

    public function taskCompletedAnalytics3($year)
    {
        $taskCompletedBeforeDueDateArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        $taskCompletedAfterDueDateArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);

        $tasks = Task::where('updated_at', 'like', ''.$year.'%')->where('status', 3)->where('personInCharge', Auth::id())->get();
        foreach ($tasks as $task) {
            $month = date('m', strtotime($task->updated_at));
            if($task->updated_at->format('Y-m-d') <= $task->dueDate){
                $taskCompletedBeforeDueDateArrays[$month] = $taskCompletedBeforeDueDateArrays[$month] + 1;
            }
            else{
                $taskCompletedAfterDueDateArrays[$month] = $taskCompletedAfterDueDateArrays[$month] + 1;
            }
        }

        return [array_values($taskCompletedBeforeDueDateArrays), array_values($taskCompletedAfterDueDateArrays)];
    }

    public function taskApprovedAndRejectedAnalytics3($year)
    {
        $taskApprovedArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        $taskRejectedArrays = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);
        
        $approvedTasks = Task::where('updated_at', 'like', ''.$year.'%')->where('status', 3)->where('personInCharge', Auth::id())->get();
        foreach ($approvedTasks as $approvedTask) {
            $month = date('m', strtotime($approvedTask->updated_at));
            $taskApprovedArrays[$month] = $taskApprovedArrays[$month] + 1;
        }

        $rejectedTasks = Task::where('updated_at', 'like', ''.$year.'%')->where('status', 2)->where('personInCharge', Auth::id())->get();
        foreach ($rejectedTasks as $rejectedTask) {
            $month = date('m', strtotime($rejectedTask->updated_at));
            $taskRejectedArrays[$month] = $taskRejectedArrays[$month] + 1;
        }

        return [array_values($taskApprovedArrays), array_values($taskRejectedArrays)];
    }
}
