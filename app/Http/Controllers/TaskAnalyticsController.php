<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskAnalyticsController extends Controller
{
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
}
