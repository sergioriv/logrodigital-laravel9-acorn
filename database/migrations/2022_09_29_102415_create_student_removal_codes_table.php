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
        Schema::create('student_removal_codes', function (Blueprint $table) {
            $table->unsignedBigInteger('student_id');
            $table->string('code', 10);
            $table->unsignedBigInteger('user_id');
            $table->timestamp('created_at')->nullable();

            $table->foreign('student_id')
                ->references('id')->on('students')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')->on('users')
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
        Schema::dropIfExists('student_removal_codes');
    }
};
