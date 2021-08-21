<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function getHrManagerEmail()
    {
        $emails = array();

        $hrDepartments = Department::all()->where('departmentName', 'Human Resource');
        foreach($hrDepartments as $hrDepartment){
            $hrManagers = User::all()->where('department', $hrDepartment->id)->where('role', 1);
        }
        foreach ($hrManagers as $hrManager){
            array_push($emails, $hrManager->email);
        }

        return $emails;
    }

    public function getStatus(){
        $status = null;

        if($this->leaveStatus == 0){
            $status = "Waiting Approval";
        }
        elseif($this->leaveStatus == 1){
            $status = "Rejected";
        }
        elseif($this->leaveStatus == 2){
            $status = "Approved";
        }

        return $status;
    }
}