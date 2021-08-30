<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ClaimRequest extends Model
{
    use HasFactory;

    public function getClaimType(){
        return $this->belongsTo(ClaimType::class, "claimType");
    }

    public function getEmployee(){
        return $this->belongsTo(User::class, "claimEmployee");
    }

    public function getManager(){
        return $this->belongsTo(User::class, "claimManager");
    }

    public function getStatus(){
        $status = null;

        if($this->claimStatus == 0 && $this->claimManager == Auth::user()->id){
            $status = "To be approve";
        }
        elseif($this->claimStatus == 0){
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
