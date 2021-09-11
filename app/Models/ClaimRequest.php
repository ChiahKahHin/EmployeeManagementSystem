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

    public function getDelegateManager(){
        return $this->belongsTo(User::class, "claimDelegateManager");
    }

    public function getStatus(){
        $status = null;
        $delegated = (Auth::id() == $this->claimDelegateManager && $this->claimDelegateManager != null) ? " <i>(Delegated)</i>" : null ;

        if($this->claimStatus == 0 && $this->claimManager == Auth::id()){
            $status = "To be approve".$delegated;
        }
        elseif($this->claimStatus == 0){
            $status = "Waiting Approval".$delegated;
        }
        elseif($this->claimStatus == 1){
            $status = "Rejected".$delegated;
        }
        elseif($this->claimStatus == 2){
            $status = "Approved".$delegated;
        }
        elseif($this->claimStatus == 3){
            $status = "Cancelled".$delegated;
        }

        return $status;
    }
}
