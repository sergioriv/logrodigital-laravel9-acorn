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
        Schema::create('period_types', function (Blueprint $table) {
            /*
             * Study, Resit
             */
            $table->id();
            $table->string('name');
        });

        Schema::create('school', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nit', 20);
            $table->string('contact_email', 100)->nullable();
            $table->string('contact_telephone', 20)->nullable();
            $table->unsignedSmallInteger('city')->nullable();
            $table->string('badge')->nullable();
            $table->unsignedMediumInteger('students');
            $table->timestamps();
        });


        Schema::create('teachers', function (Blueprint $table) {/* [User] */
            $table->unsignedBigInteger('id');
            $table->timestamps();

            $table->primary('id');

            $table->foreign('id')
                    ->references('id')
                    ->on('users')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });

        Schema::create('students', function (Blueprint $table) {/* [User] */
            $table->unsignedBigInteger('id');
            $table->timestamps();

            $table->primary('id');

            $table->foreign('id')
                    ->references('id')
                    ->on('users')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });

        Schema::create('psychology', function (Blueprint $table) {/* [User] */
            $table->unsignedBigInteger('id');
            $table->string('telephone', 20)->nullable();
            $table->timestamps();

            $table->primary('id');

            $table->foreign('id')
                    ->references('id')
                    ->on('users')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });

        Schema::create('coordinators', function (Blueprint $table) {/* [User] */
            $table->unsignedBigInteger('id');
            $table->string('telephone', 20)->nullable();
            $table->timestamps();

            $table->primary('id');

            $table->foreign('id')
                    ->references('id')
                    ->on('users')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });



        Schema::create('school_years', function (Blueprint $table) {/* Ej: 2022, 2023  */
            $table->id();
            $table->string('name');
            $table->boolean('available')->nullable();
            $table->timestamps();
        });

        Schema::create('headquarters', function (Blueprint $table) {/* Ej: principal, san jose, corzo */
            $table->id();
            $table->string('name');
            $table->boolean('available');
            $table->timestamps();
        });

        Schema::create('study_times', function (Blueprint $table) {/* Ej: morning, afternoon, night */
            $table->id();
            $table->string('name');
        });

        Schema::create('study_years', function (Blueprint $table) {/* Ej: sexto, septimo, octavo */
            $table->id();
            $table->string('name');
            $table->boolean('available');
        });

        Schema::create('resource_areas', function (Blueprint $table) {/* Ej: ciencias naturales, matematicas */
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('resource_subjects', function (Blueprint $table) {/* Ej: biologia, geometria */
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('subjects', function (Blueprint $table) {/* school_years, resource_areas, resource_subjects */
            $table->id();
            $table->unsignedBigInteger('school_year_id');
            $table->unsignedBigInteger('resource_area_id');
            $table->unsignedBigInteger('resource_subject_id');
            $table->timestamps();

            $table->foreign('school_year_id')
                    ->references('id')
                    ->on('school_years')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');

            $table->foreign('resource_area_id')
                    ->references('id')
                    ->on('resource_areas')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');

            $table->foreign('resource_subject_id')
                    ->references('id')
                    ->on('resource_subjects')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');
        });

        Schema::create('groups', function (Blueprint $table) {/* school_years, headquarters, study_times, study_years */
            $table->id();
            $table->unsignedBigInteger('school_year_id');
            $table->unsignedBigInteger('headquarters_id');
            $table->unsignedBigInteger('study_time_id');
            $table->unsignedBigInteger('study_year_id');
            $table->unsignedBigInteger('teacher_id');
            $table->string('name');
            $table->unsignedSmallInteger('student_quantity')->default(0);
            $table->timestamps();

            $table->foreign('school_year_id')
                    ->references('id')
                    ->on('school_years')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');

            $table->foreign('headquarters_id')
                    ->references('id')
                    ->on('headquarters')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');

            $table->foreign('study_time_id')
                    ->references('id')
                    ->on('study_times')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');

            $table->foreign('study_year_id')
                    ->references('id')
                    ->on('study_years')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');

            $table->foreign('teacher_id')
                    ->references('id')
                    ->on('teachers')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');
        });

        Schema::create('teacher_subject_groups', function (Blueprint $table) {/* school_years, teachers, subjects, groups */
            $table->id();
            $table->unsignedBigInteger('school_year_id');
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('group_id');
            $table->unsignedTinyInteger('work_schedule')->nullable();
            $table->timestamps();

            $table->foreign('school_year_id')
                    ->references('id')
                    ->on('school_years')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');

            $table->foreign('teacher_id')
                    ->references('id')
                    ->on('teachers')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');

            $table->foreign('subject_id')
                    ->references('id')
                    ->on('subjects')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');

            $table->foreign('group_id')
                    ->references('id')
                    ->on('groups')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');
        });

        Schema::create('group_students', function (Blueprint $table) {/* groups, students */
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('student_id');
            $table->timestamps();

            $table->foreign('group_id')
                    ->references('id')
                    ->on('groups')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');

            $table->foreign('student_id')
                    ->references('id')
                    ->on('students')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');
        });

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

        Schema::create('periods', function (Blueprint $table) {/* school_years, headquarters, study_times, period_types */
            $table->id();
            $table->unsignedBigInteger('school_year_id');
            $table->unsignedBigInteger('headquarters_id');
            $table->unsignedBigInteger('study_time_id');
            $table->unsignedBigInteger('period_type_id');
            $table->string('name');
            $table->date('start');
            $table->date('end');
            $table->timestamps();

            $table->foreign('school_year_id')
                    ->references('id')
                    ->on('school_years')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');

            $table->foreign('headquarters_id')
                    ->references('id')
                    ->on('headquarters')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');

            $table->foreign('study_time_id')
                    ->references('id')
                    ->on('study_times')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');

            $table->foreign('period_type_id')
                    ->references('id')
                    ->on('period_types')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');
        });

        Schema::create('grades', function (Blueprint $table) {/* teacher_subject_groups, periods, students */
            $table->id();
            $table->unsignedBigInteger('teacher_subject_group_id');
            $table->unsignedBigInteger('period_id');
            $table->unsignedBigInteger('student_id');
            $table->decimal('grade', 3, 2, true)->default(0.0);
            $table->timestamps();

            $table->foreign('teacher_subject_group_id')
                    ->references('id')
                    ->on('teacher_subject_groups')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');

            $table->foreign('period_id')
                    ->references('id')
                    ->on('periods')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');

            $table->foreign('student_id')
                    ->references('id')
                    ->on('students')
                    ->onUpdate('restrict')
                    ->onDelete('restrict');
        });




        /* PENDIENTES */
        // Schema::create('main_themes', function (Blueprint $table) {/* teachers */
        //     $table->id();
        //     $table->string('name');
        //     $table->timestamps();
        // });

        // Schema::create('descriptors', function (Blueprint $table) {/* main_themes */
        //     $table->id();
        //     $table->timestamps();
        // });




    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('grades');
        Schema::dropIfExists('periods');
        Schema::dropIfExists('study_year_subjects');
        Schema::dropIfExists('group_students');
        Schema::dropIfExists('teacher_subject_groups');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('resource_subjects');
        Schema::dropIfExists('resource_areas');
        Schema::dropIfExists('study_years');
        Schema::dropIfExists('study_times');
        Schema::dropIfExists('headquarters');
        Schema::dropIfExists('school_years');
        Schema::dropIfExists('coordinators');
        Schema::dropIfExists('psychology');
        Schema::dropIfExists('students');
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('school');
        Schema::dropIfExists('period_types');
    }
};
