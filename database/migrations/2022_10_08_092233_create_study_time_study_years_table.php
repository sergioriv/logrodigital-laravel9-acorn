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
        Schema::create('study_time_study_years', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('study_time_id');
            $table->unsignedBigInteger('study_year_id');
            $table->timestamps();

            $table->foreign('study_time_id')->references('id')->on('study_times');
            $table->foreign('study_year_id')->references('id')->on('study_years');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('study_time_study_years');
    }
};
