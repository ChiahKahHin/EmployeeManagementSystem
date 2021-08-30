<?php

use App\Models\LeaveType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('leaveType')->unique();
            $table->integer('leaveLimit')->nullable();
            $table->string('gender');
            $table->timestamps();
        });

        LeaveType::create([
            'leaveType' => 'Annual Leave',
            'leaveLimit' => 18,
            'gender' => 'All',
        ]);

        LeaveType::create([
            'leaveType' => 'Carried Forward Leave',
            'gender' => 'All',
        ]);

        LeaveType::create([
            'leaveType' => 'Medical Leave',
            'leaveLimit' => 3,
            'gender' => 'All',
        ]);

        LeaveType::create([
            'leaveType' => 'Maternity Leave',
            'leaveLimit' => 30,
            'gender' => 'Female',
        ]);

        LeaveType::create([
            'leaveType' => 'Paternity Leave',
            'leaveLimit' => 10,
            'gender' => 'Male',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_types');
    }
}
