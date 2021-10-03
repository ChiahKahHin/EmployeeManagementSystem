<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarriedForwardLeaveRule extends Model
{
    use HasFactory;

    protected $table = "carried_forward_leave_rule";

    protected $fillable = [
        'id',
        'ableCF',
        'recurring',
        'leaveLimit',
        'useBefore',
        'approval',
        'startDate',
        'endDate',
    ];
}
