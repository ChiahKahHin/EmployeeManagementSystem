<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarriedForwardLeave extends Model
{
    use HasFactory;

    protected $table = "carried_forward_leaves";

    protected $fillable = [
        'employeeID',
        'leaveLimit',
    ];
}
