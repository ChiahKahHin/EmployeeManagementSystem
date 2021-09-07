<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TrainingProgram extends Model
{
    use HasFactory;

    protected $table = "training_programs";

    public function getStatus()
    {
        $status = null;

        if ($this->status == 0) {
            $status = "Ongoing";
        }
        else{
            $status = "Completed";
        }
        
        return $status;
    }

    public function getDepartment() {
        return $this->belongsTo(Department::class, "department");
    }

    public function getAttendees(){
        return $this->hasMany(TrainingAttendee::class, "trainingProgram");
    }

    public function getRegistrationStatus(){
        
        $count = $this->getAttendees->filter(function ($value, $key)
        {
            return $value->employeeID == Auth::id();
        })->count();
        
        if ($count == 0 && $this->status == 1) {
            return "Close for registration";
        }
        elseif ($count == 1 && $this->status == 1){
            return "Training program ended";
        }
        else if ($count == 0) {
            return "Not yet register";
        }
        else{
            return "Registered";
        }
    }
}
