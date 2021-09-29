<?php

use App\Models\CarriedForwardLeaveRule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarriedForwardLeaveRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carried_forward_leave_rule', function (Blueprint $table) {
            $table->id();
            $table->integer('leaveLimit');
            $table->date('useBefore');
            $table->integer('approval');
            $table->date('startDate');
            $table->date('endDate');
            $table->timestamps();
        });

        CarriedForwardLeaveRule::create([
            "id" => 1,
            "leaveLimit" => 12,
            "useBefore" => "2022-3-31",
            "approval" => 1,
            "startDate" => "2021-11-1",
            "endDate" => "2021-12-31"
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carried_forward_leave_rule');
    }
}
