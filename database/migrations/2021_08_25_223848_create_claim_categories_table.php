<?php

use App\Models\ClaimCategory;
use Faker\Provider\Medical;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_categories', function (Blueprint $table) {
            $table->id();
            $table->string('claimCategory')->unique();
            $table->timestamps();
        });

        ClaimCategory::create([
            "claimCategory" => "Medical Claim"
        ]);

        ClaimCategory::create([
            "claimCategory" => "Meal Claim"
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('claim_categories');
    }
}
