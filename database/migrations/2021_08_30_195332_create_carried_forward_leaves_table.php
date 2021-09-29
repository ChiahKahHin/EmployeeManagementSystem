<?php

use App\Models\CarriedForwardLeave;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarriedForwardLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carried_forward_leaves', function (Blueprint $table) {
            $table->id();
            $table->integer('employeeID');
            $table->integer('managerID')->nullable();
            $table->integer('delegateManagerID')->nullable();
            $table->string('rejectedReason')->nullable();
            $table->double('leaveLimit');
            $table->date('useBefore');
            $table->integer('status');
            $table->timestamps();
        });

        // CarriedForwardLeave::create([
        //     'employeeID' => 6,
        //     'managerID' => 4,
        //     'leaveLimit' => 5,
        //     'useBefore' => '2022-3-31',
        //     'status' => 1
        // ]);

        // CarriedForwardLeave::create([
        //     'employeeID' => 7,
        //     'managerID' => 4,
        //     'leaveLimit' => 3,
        //     'useBefore' => '2022-3-31',
        //     'status' => 1
        // ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carried_forward_leaves');
    }
}
