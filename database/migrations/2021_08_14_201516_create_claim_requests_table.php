<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateClaimRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('claimType');
            $table->string('claimAmount');
            $table->date('claimDate');
            $table->text('claimDescription');
            $table->string('claimRejectedReason')->nullable();
            $table->integer('claimEmployee');
            $table->integer('claimManager');
            $table->integer('claimDelegateManager')->nullable();
            $table->integer('claimStatus');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE claim_requests ADD claimAttachment LONGBLOB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('claim_requests');
    }
}
