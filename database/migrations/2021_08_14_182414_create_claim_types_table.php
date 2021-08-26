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
            $table->integer('claimCategory');
            $table->string('claimType')->unique();
            $table->double('claimAmount');
            $table->string('claimPeriod');
            $table->timestamps();
        });

        ClaimType::create([
            'claimCategory' => 1,
            'claimType' => "Medical",
            'claimAmount' => 2000,
            'claimPeriod' => "Per Annum",
        ]);

        ClaimType::create([
            'claimCategory' => 1,
            'claimType' => "Dental",
            'claimAmount' => 200,
            'claimPeriod' => "Per Claim",
        ]);

        ClaimType::create([
            'claimCategory' => 2,
            'claimType' => "Fuel",
            'claimAmount' => 50,
            'claimPeriod' => "Per Claim",
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
