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
        $recipients = explode(',', $this->memoRecipient);
        $departments = Department::all()->whereIn('id', $recipients);
        $departmentName = null;
        $x = 0;

        foreach ($departments as $department) {
            $departmentName .= $department->departmentName;
            $x++;
            if($x != count($departments)){
                $departmentName .= ", ";
            }
        }

        return $departmentName;
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
