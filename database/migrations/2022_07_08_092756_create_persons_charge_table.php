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

        Schema::create('kinships', function (Blueprint $table) {
            $table->id();
            $table->string('name', 15);
        });

        Schema::create('persons_charge', function (Blueprint $table) { /* users, students, cities, kinships */
            $table->unsignedBigInteger('id');
            $table->unsignedBigInteger('student_id');
            $table->string('name');
            $table->string('email');
            $table->string('document', 20)->nullable();
            $table->unsignedBigInteger('expedition_city_id')->nullable();
            $table->unsignedBigInteger('residence_city_id')->nullable();
            $table->string('address', 100)->nullable();
            $table->string('telephone', 20)->nullable();
            $table->string('cellphone', 20)->nullable();
            $table->date('birthdate')->nullable();
            $table->unsignedBigInteger('kinship_id');
            $table->string('occupation')->nullable();
            $table->timestamps();

            $table->primary('id');

            $table->foreign('id')
                    ->references('id')
                    ->on('users')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreign('student_id')
                    ->references('id')
                    ->on('students')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreign('expedition_city_id')
                    ->references('id')
                    ->on('cities')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreign('residence_city_id')
                    ->references('id')
                    ->on('cities')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreign('kinship_id')
                    ->references('id')
                    ->on('kinships')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persons_charge');
        Schema::dropIfExists('kinships');
    }
};
