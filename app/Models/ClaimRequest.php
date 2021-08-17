<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimRequest extends Model
{
    use HasFactory;

    public function getClaimType(){
        return $this->belongsTo(BenefitClaim::class, "claimType");
    }

    public function getEmployee(){
        return $this->belongsTo(User::class, "claimEmployee");
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

        if($this->claimStatus == 0){
            $status = "Waiting Approval";
        }
        elseif($this->claimStatus == 1){
            $status = "Rejected";
        }
        elseif($this->claimStatus == 2){
            $status = "Approved";
        }

        return $status;
    }
}
