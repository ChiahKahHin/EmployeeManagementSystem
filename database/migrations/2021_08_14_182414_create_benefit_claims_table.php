<?php

use App\Models\BenefitClaim;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBenefitClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('benefit_claims', function (Blueprint $table) {
            $table->id();
            $table->string('claimType')->unique();
            $table->double('claimAmount');
            $table->timestamps();
        });

        BenefitClaim::create([
            'claimType' => "Medical",
            'claimAmount' => 200
        ]);

        BenefitClaim::create([
            'claimType' => "Dental",
            'claimAmount' => 100
        ]);

        BenefitClaim::create([
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
