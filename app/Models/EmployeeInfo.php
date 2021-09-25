<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeInfo extends Model
{
    use HasFactory;

    protected $table = "employee_info";

    protected $primaryKey = "employeeID";
 
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employeeID',
        'firstname',
        'lastname',
        'contactNumber',
        'dateOfBirth',
        'gender',
        'address',
        'ic',
        'nationality',
        'citizenship',
        'religion',
        'race',
        'emergencyContactName',
        'emergencyContactNumber',
        'emergencyContactAddress',
        'maritalStatus',
        'spouseName',
        'spouseDateOfBirth',
        'spouseIC',
        'dateOfMarriage',
        'spouseOccupation',
        'spouseContactNumber',
        'spouseResidentStatus',
    ];
}
