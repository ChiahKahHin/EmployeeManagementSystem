<?php

use App\Models\Position;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('positionName')->unique();
            $table->timestamps();
        });

        Position::create([
            "positionName" => "Chief Executive Officer"
        ]);

        Position::create([
            "positionName" => "Human Resource Manager"
        ]);
        
        Position::create([
            "positionName" => "Department Manager"
        ]);

        Position::create([
            "positionName" => "Manager"
        ]);

        Position::create([
            "positionName" => "Secretary"
        ]);

        Position::create([
            "positionName" => "Staff"
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('positions');
    }
}
