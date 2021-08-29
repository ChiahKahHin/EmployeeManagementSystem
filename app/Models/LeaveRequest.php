<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $table = "leave_requests";

    public function getLeaveType(){
        return $this->belongsTo(LeaveType::class, "leaveType");
    }

    public function getEmployee(){
        return $this->belongsTo(User::class, "employeeID");
    }

    public function getManager(){
        return $this->belongsTo(User::class, "manager");
    }

    public function getStatus(){
        $status = null;
        if($this->leaveStatus == 0 && $this->manager == Auth::user()->id){
            $status = "To be approve";
        }
        elseif($this->leaveStatus == 0){
            $status = "Waiting Approval";
        }
        elseif($this->leaveStatus == 1){
            $status = "Rejected";
        }
        elseif($this->leaveStatus == 2){
            $status = "Approved";
        }
        elseif($this->leaveStatus == 3){
            $status = "Cancelled";
        }

        return $status;
    }
}
