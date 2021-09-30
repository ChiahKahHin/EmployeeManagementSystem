<?php

namespace App\Http\Controllers;

use App\Mail\TaskNotificationMail;
use App\Models\PublicHoliday;
use App\Models\RejectedTask;
use App\Models\Task;
use App\Models\User;
use App\Models\WorkingDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['employee:admin'])->only(['deleteTask']);
        $this->middleware(['employee:admin,hrmanager,manager'])->only(['addTaskForm', 'addTask', 'editTaskForm', 'editTask', 'approveTask', 'rejectTask']);
    }

    public function addTaskForm()
    {
        if(Auth::user()->isAdmin()){
            $personInCharges = User::where('id', '!=' , Auth::id())
                                    ->get();
        }
        else{
            $personInCharges = User::where('reportingManager', Auth::id())
                                    ->get();
        }

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

        //Check conflict with public holiday
        /*$numberOfPublicHolidays = 0;
        $publicHolidays = PublicHoliday::distinct()->get('date');
        foreach ($publicHolidays as $publicHoliday) {
            if($publicHoliday->date == $request->dueDate){
                $numberOfPublicHolidays++ ;
                $publicHolidayDate = $publicHoliday->date;
            }
        }
        
        if ($numberOfPublicHolidays > 0) {
            return redirect()->route('addTask')->with('error', 'Please do not select public holiday for the task due date ')
                                                     ->with('error1', "Public Holiday: ". $publicHolidayDate);
        }

        //Check conflict with non-working day
        $numberOfNonWorkingDays = 0;
        $nonWorkingDays = WorkingDay::all()->where('status', 0);

        foreach ($nonWorkingDays as $nonWorkingDay) {
            if($nonWorkingDay->workingDay == date("l", strtotime($request->dueDate))){
                $numberOfNonWorkingDays++;
            }
        }
        
        if ($numberOfNonWorkingDays > 0) {
            return redirect()->route('addTask')->with('error', 'Please do not select non-working day for the task due date');
        }*/

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
            $tasks = Task::with('getPersonInCharge.getEmployeeInfo')->orderBy('status', 'ASC')->orderBy('dueDate', 'ASC')->get();
        }
        elseif(Auth::user()->isEmployee()){
            $tasks = Task::with('getManager.getEmployeeInfo')->orderBy('status', 'ASC')->orderBy('dueDate', 'ASC')->where('personInCharge', Auth::user()->id)->get();
        }
        else{
            $tasks = Task::with('getPersonInCharge.getEmployeeInfo')
                          ->orderBy('status', 'ASC')
                          ->orderBy('dueDate', 'ASC')
                          ->orWhere('personInCharge', Auth::user()->id)
                          ->orWhere('managerID', Auth::user()->id)
                          ->orWhere('delegateManagerID', Auth::user()->id)
                          ->get();
        }

        return view('manageTask', ['tasks' => $tasks]);
    }

    public function editTaskForm($id)
    {
        $task = Task::findOrFail($id);
        if(Auth::user()->isAdmin() || Auth::id() == $task->managerID || Auth::id() == $task->delegateManagerID){
            $personInCharges = User::where('reportingManager', Auth::id())
                                    ->get();
            
            return view('editTask', ['task' => $task, 'personInCharges' => $personInCharges]);
        }
        return redirect()->route('manageTask');
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

        //Check conflict with public holiday
        /*$numberOfPublicHolidays = 0;
        $publicHolidays = PublicHoliday::distinct()->get('date');
        foreach ($publicHolidays as $publicHoliday) {
            if($publicHoliday->date == $request->dueDate){
                $numberOfPublicHolidays++ ;
                $publicHolidayDate = $publicHoliday->date;
            }
        }
        
        if ($numberOfPublicHolidays > 0) {
            return redirect()->route('editTask', ['id' => $id])->with('error', 'Please do not select public holiday for the task due date ')
                                                     ->with('error1', "Public Holiday: ". $publicHolidayDate);
        }

        //Check conflict with non-working day
        $numberOfNonWorkingDays = 0;
        $nonWorkingDays = WorkingDay::all()->where('status', 0);

        foreach ($nonWorkingDays as $nonWorkingDay) {
            if($nonWorkingDay->workingDay == date("l", strtotime($request->dueDate))){
                $numberOfNonWorkingDays++;
            }
        }
        
        if ($numberOfNonWorkingDays > 0) {
            return redirect()->route('editTask', ['id' => $id])->with('error', 'Please do not select non-working day for the task due date');
        }*/

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
        if(Auth::user()->isAdmin() || Auth::id() == $task->managerID || Auth::id() == $task->delegateManagerID){
            $task->status = 3;
            $task->save();

            $email = $task->getEmail($task->personInCharge);

            Mail::to($email)->send(new TaskNotificationMail($task));

            return redirect()->route('viewTask', ['id' => $id]);
        }

        return redirect()->route('manageTask');
    }

    public function rejectTask($id, $reason)
    {
        $task = Task::find($id);
        if(Auth::user()->isAdmin() || Auth::id() == $task->managerID || Auth::id() == $task->delegateManagerID){
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
        
        return redirect()->route('manageTask');
    }

    public function completeTask($id)
    {
        $task = Task::find($id);
        if(Auth::user()->isAdmin() || Auth::id() == $task->personInCharge){
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
        
        return redirect()->route('manageTask');
    }
}