<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProgram extends Model
{
    use HasFactory;

    protected $table = "training_programs";

    public function getStatus()
    {
        $status = null;

        if ($this->status == 0) {
            $status = "Ongoing";
        }
        else{
            $status = "Completed";
        }
        
        return $status;
    }
}
