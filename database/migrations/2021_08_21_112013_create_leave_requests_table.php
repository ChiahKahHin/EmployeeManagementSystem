<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('leaveType');
            $table->integer('employeeID');
            $table->integer('managerID');
            $table->integer('delegateManagerID')->nullable();
            $table->date('leaveStartDate');
            $table->date('leaveEndDate');
            $table->double('leaveDuration');
            $table->string('leavePeriod');
            $table->string('leaveDescription');
            $table->string('leaveRejectedReason')->nullable();
            $table->integer('leaveReplacement');
            $table->integer('leaveStatus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_requests');
    }
}
