<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) { /* school_years */
            $table->id();
            $table->enum('provider', [
                'microsoft',
                'google',
                'logro.digital', // dominio insitucional
            ])->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('avatar')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->unsignedBigInteger('school_year_id')->nullable();
            $table->timestamps();

            $table->foreign('school_year_id')
                    ->references('id')
                    ->on('school_years')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');
        });
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
};
