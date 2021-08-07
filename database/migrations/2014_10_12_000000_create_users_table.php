<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('contactNumber');
            $table->date('dateOfBirth');
            $table->string('password');
            $table->integer('role');
            $table->rememberToken();
            $table->timestamps();
        });

        User::create([
            "username" => "admin",
            "email" => "admin@gmail.com",
            "firstname" => "Kah Hin",
            "lastname" => "Chiah",
            "contactNumber" => "012-4783997",
            "dateOfBirth" => "2000-7-7",
            "password" => Hash::make("123"),
            "role" => 0
        ]);

        // User::create([
        //     "username" => "manager",
        //     "email" => "manager@gmail.com",
        //     "password" => Hash::make("123"),
        //     "role" => 1
        // ]);

        // User::create([
        //     "username" => "emp",
        //     "email" => "emp@gmail.com",
        //     "password" => Hash::make("123"),
        //     "role" => 2
        // ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
