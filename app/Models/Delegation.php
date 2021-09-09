<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delegation extends Model
{
    use HasFactory;

    protected $table = "delegations";

    public function getManager(){
        return $this->belongsTo(User::class, "managerID");
    }

    public function getDelegateManager(){
        return $this->belongsTo(User::class, "delegateManagerID");
    }

    public function getStatus(){
        $status = null;

        if($this->status == 0){
            $status = "Scheduling";
        }
        elseif($this->status == 1){
            $status = "Ongoing";
        }
        elseif($this->status == 2){
            $status = "Completed";
        }
        elseif($this->status == 3){
            $status = "Cancelled";
        }

        return $status;
    }
}
