<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory;

    protected $table = "tasks";

    public function getPersonInCharge(){
        return $this->belongsTo(User::class, "personInCharge");
    }

    public function getManager(){
        return $this->belongsTo(User::class, "managerID");
    }

    public function getDelegateManager(){
        return $this->belongsTo(User::class, "delegateManagerID");
    }

    public function getRejectedReasons(){
        return $this->hasMany(RejectedTask::class, "taskID");
    }

    public function getEmail($id){
        $user = User::find($id);
        
        return $user->email;
    }

    public function getStatus(){
        $taskStatus = null;
        $delegated = (Auth::id() == $this->delegateManagerID && $this->delegateManagerID != null) ? " <i>(Delegated)</i>" : null ;

        if($this->dueDate < date("Y-m-d") && $this->status == 0){
            $taskStatus = "Overdue".$delegated;
        }
        elseif($this->status == 1 && $this->managerID == Auth::id() || $this->status == 1 && $this->delegateManagerID == Auth::id()){
            $taskStatus = "To be approve".$delegated;
        }
        elseif($this->status == 0){
            $taskStatus = "Pending".$delegated;
        }
        elseif($this->status == 1){
            $taskStatus = "Waiting Approval".$delegated;
        }
        elseif($this->status == 2){
            $taskStatus = "Rejected".$delegated;
        }
        elseif($this->status == 3){
            $taskStatus = "Completed".$delegated;
        }

        return $taskStatus;
    }

    public function getTaskStatus()
    {
        if($this->status == 0){
            $taskStatus = "Pending";
        }
        elseif($this->status == 1){
            $taskStatus = "Waiting Approval";
        }
        elseif($this->status == 2){
            $taskStatus = "Rejected";
        }
        elseif($this->status == 3){
            $taskStatus = "Completed";
        }
        return $taskStatus;
    }
}
