<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLES = [
        0 => 'admin',
        1 => 'hrmanager',
        2 => 'manager',
        3 => 'employee',
    ];

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
        'department',
        'position',
        'role',
        'reportingManager',
        'password',
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

    public function getPosition() {
        return $this->belongsTo(Position::class, "position");
    }

    public function getEmployeeInfo(){
        return $this->hasOne(EmployeeInfo::class, "userID");
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
            $roleName = "Super Admin";
        }
        elseif($this->role == 1){
            $roleName = "HR Manager";
        }
        elseif($this->role == 2){
            $roleName = "Manager";
        }
        else{
            $roleName = "Employee";
        }
        return $roleName;
    }

    public function getShortRoleName()
    {
            return self::ROLES[$this->role];
    }

    public function getEmployeeEmail($department = null)
    {
        $emails = array();

        if ($department == null) {
            $empEmails = User::all();
        }
        else{
            $empEmails = User::all()->whereIn('department', $department);
        }
        foreach ($empEmails as $empEmail) {
            array_push($emails, $empEmail->email);
        }

        return $emails;
    }

    public function getFullName($id = null)
    {
        if($id == null){
            $fullName = $this->getEmployeeInfo->firstname;
            $fullName .= " ";
            $fullName .= $this->getEmployeeInfo->lastname;
        }
        else{
            $user = User::find($id);
            $fullName = $user->getEmployeeInfo->firstname;
            $fullName .= " ";
            $fullName .= $user->getEmployeeInfo->lastname;
        }

        return ucwords($fullName);
    }

    public function isAccess(...$roles) {
        return in_array(Str::lower(self::ROLES[$this->role]), $roles);
    }
}
