<?php

use App\Models\ClaimType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_types', function (Blueprint $table) {
            $table->id();
            $table->string('claimType')->unique();
            $table->double('claimAmount');
            $table->timestamps();
        });

        ClaimType::create([
            'claimType' => "Medical",
            'claimAmount' => 200
        ]);

        ClaimType::create([
            'claimType' => "Dental",
            'claimAmount' => 100
        ]);

        ClaimType::create([
            'claimType' => "Fuel",
            'claimAmount' => 150
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('benefit_claims');
    }
}
