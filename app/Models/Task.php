<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = "tasks";

    public function getPersonInCharge(){
        return $this->belongsTo(User::class, "personInCharge");
    }

    public function getManager(){
        return $this->belongsTo(User::class, "manager");
    }

    public function getReportingManager($id){
        $user = User::find($id);
        return $user->reportingManager;
    }

    public function getEmail($id){
        $user = User::find($id);
        
        return $user->email;
    }

    public function getStatus(){
        $taskStatus = null;

        if($this->dueDate >= date("Y-m-d")){
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
        }
        else{
            $taskStatus = "Overdue";
        }

        return $taskStatus;
    }
}
