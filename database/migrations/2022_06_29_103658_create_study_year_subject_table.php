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
        Schema::create('study_year_subjects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_year_id');
            $table->unsignedBigInteger('study_year_id');
            $table->unsignedBigInteger('subject_id');

            $table->foreign('school_year_id')
                    ->references('id')
                    ->on('school_years')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreign('study_year_id')
                    ->references('id')
                    ->on('study_years')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreign('subject_id')
                    ->references('id')
                    ->on('subjects')
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
        Schema::dropIfExists('study_year_subject');
    }
};
