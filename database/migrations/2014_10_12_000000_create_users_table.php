<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('two_factor')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });

        $user = new \App\Models\User();

        $user->create([
            'name' => 'Anya',
            'email' => 'anya@test.com',
            'password' => \Illuminate\Support\Facades\Hash::make('secret'),
            'two_factor' => 1
        ]);

        $user->create([
            'name' => 'Brian',
            'email' => 'brian@test.com',
            'password' => \Illuminate\Support\Facades\Hash::make('secret')
        ]);
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
