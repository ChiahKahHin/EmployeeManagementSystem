<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingAttendee extends Model
{
    use HasFactory;

    protected $table = "training_attendees";

    public function getEmployee(){
        return $this->belongsTo(User::class, "employeeID");
    }
}
