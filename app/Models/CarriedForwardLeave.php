<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CarriedForwardLeave extends Model
{
    use HasFactory;

    protected $table = "carried_forward_leaves";

    protected $fillable = [
        'employeeID',
        'managerID',
        'leaveLimit',
        'useBefore',
        'status'
    ];

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
        $delegated = (Auth::id() == $this->delegateManagerID && $this->delegateManagerID != null) ? " <i>(Delegated)</i>" : null ;
        
        if($this->status == 0){
            return "Waiting Approval".$delegated;
        }
        elseif($this->status == 1){
            return "Rejected".$delegated;
        }
        elseif($this->status == 2){
            return "Approved".$delegated;
        }
    }
}
