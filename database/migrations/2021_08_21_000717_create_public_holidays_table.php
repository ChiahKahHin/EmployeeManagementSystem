<?php

use App\Models\PublicHoliday;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->date('date');
            $table->timestamps();
        });

        PublicHoliday::create([
            'name' => 'National Day',
            'date' => '2021-08-31',
        ]);

        PublicHoliday::create([
            'name' => 'Malaysia Day',
            'date' => '2021-09-16',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('public_holidays');
    }
}
