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
        /* Schema::create('student_file_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
        }); */

        Schema::create('student_files', function (Blueprint $table) { /* students, student_file_types, users */
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('student_file_type_id');
            $table->string('url');
            $table->string('url_absolute');
            $table->boolean('checked')->nullable();
            $table->boolean('renewed')->nullable();
            $table->unsignedBigInteger('approval_user_id')->nullable();
            $table->timestamp('approval_date')->nullable();
            $table->unsignedBigInteger('creation_user_id');
            $table->timestamps();

            $table->foreign('student_id')
                    ->references('id')
                    ->on('students')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreign('student_file_type_id')
                    ->references('id')
                    ->on('student_file_types')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');

            $table->foreign('approval_user_id')
                    ->references('id')
                    ->on('users')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');

            $table->foreign('creation_user_id')
                    ->references('id')
                    ->on('users')
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
        Schema::dropIfExists('student_files');
    }
};
