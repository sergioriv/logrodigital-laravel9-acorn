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
        Schema::create('student_advices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('student_id');
            $table->date('date');
            $table->time('time');
            $table->enum('attendance', ['DONE', 'NOT DONE', 'SCHEDULED']);
            $table->enum('type_advice', ['INDIVIDUAL', 'GROUP', 'FAMILY']);
            $table->text('evolution')->nullable();
            $table->text('recommendations_teachers')->nullable();
            $table->date('date_limit_teacher')->nullable();
            $table->text('recommendations_family')->nullable();
            $table->enum('entity_remit', ['NINGUNA','COMISARIA', 'ICBF'])->nullable();
            $table->text('observations_for_entity')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('student_id')
                ->references('id')->on('students')
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
        Schema::dropIfExists('student_advices');
    }
};
