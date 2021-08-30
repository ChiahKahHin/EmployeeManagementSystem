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
            $table->double('leaveLimit');
            $table->timestamps();
        });

        CarriedForwardLeave::create([
            'employeeID' => 6,
            'leaveLimit' => 5
        ]);

        CarriedForwardLeave::create([
            'employeeID' => 7,
            'leaveLimit' => 3
        ]);
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
