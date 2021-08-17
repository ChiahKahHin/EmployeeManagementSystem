<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    use HasFactory;

    protected $table = "memos";

    public function getDepartmentName()
    {
        return $this->belongsTo(Department::class, "memoRecipient");
    }

    public function getStatus(){
        $status = null;

        if($this->memoStatus == 0){
            $status = "Scheduling";
        }
        else{
            $status = "Completed";
        }

        return $status;
    }
}
