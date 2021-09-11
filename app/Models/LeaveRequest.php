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
        return $this->belongsTo(User::class, "managerID");
    }

    public function getDelegateManager(){
        return $this->belongsTo(User::class, "delegateManagerID");
    }

    public function getStatus(){
        $status = null;
        $delegated = (Auth::id() == $this->delegateManagerID && $this->delegateManagerID != null) ? " <i>(Delegated)</i>" : null ;

        if($this->leaveStatus == 0 && $this->managerID == Auth::id()){
            $status = "To be approve".$delegated;
        }
        elseif($this->leaveStatus == 0){
            $status = "Waiting Approval".$delegated;
        }
        elseif($this->leaveStatus == 1){
            $status = "Rejected".$delegated;
        }
        elseif($this->leaveStatus == 2){
            $status = "Approved".$delegated;
        }
        elseif($this->leaveStatus == 3){
            $status = "Cancelled".$delegated;
        }
        elseif($this->leaveStatus == 4){
            $status = "Cancelled after approved".$delegated;
        }

        return $status;
    }
}
