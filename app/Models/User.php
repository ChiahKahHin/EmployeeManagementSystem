<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = "users";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'employeeID',
        'firstname',
        'lastname',
        'contactNumber',
        'dateOfBirth',
        'gender',
        'address',
        'department',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getDepartment() {
        return $this->belongsTo(Department::class, "department");
    }

    public function isAdmin(){
        return ($this->role == 0) ? true : false; 
    }

    public function isHrManager(){
        return ($this->role == 1) ? true : false; 
    }

    public function isManager(){
        return ($this->role == 2) ? true : false; 
    }

    public function isEmployee(){
        return ($this->role == 3) ? true : false; 
    }

    public function getRoleName(){
        $roleName = null;

        if($this->role == 0){
            $roleName = "Admin";
        }
        elseif($this->role == 1){
            $roleName = "Human Resource Manager";
        }
        elseif($this->role == 2){
            $departmentName = $this->getDepartment->departmentName;
            $roleName = $departmentName . " Manager";
        }
        else{
            $roleName = "Employee";
        }
        return $roleName;
    }

    public function getEmployeeEmail($department = null)
    {
        $emails = array();

        if ($department == null) {
            $empEmails = User::all()->whereIn('role', [1, 2, 3]);
        }
        else{
            $empEmails = User::all()->whereIn('role', [1, 2, 3])->where('department', $department);
        }
        foreach ($empEmails as $empEmail) {
            array_push($emails, $empEmail->email);
        }

        return $emails;
    }
}
