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
        Schema::create('piar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('subject_id');
            $table->text('annotation')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('student_id')
                    ->references('id')
                    ->on('students')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            $table->foreign('subject_id')
                    ->references('id')
                    ->on('subjects')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pier');
    }
};
