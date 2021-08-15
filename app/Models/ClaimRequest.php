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
    
    public function getHrManager()
    {
        $hrDepartments = Department::all()->where('departmentName', 'Human Resource');
        foreach($hrDepartments as $hrDepartment){
            $hrManager = User::all()->where('department', $hrDepartment->id)->where('role', 1);
        }

        return $hrManager;
    }

    public function getStatus(){
        $status = null;

        if($this->status == 0){
            $status = "Waiting Approval";
        }
        elseif($this->status == 1){
            $status = "Rejected";
        }
        elseif($this->status == 2){
            $status = "Approved";
        }

        return $status;
    }
}
