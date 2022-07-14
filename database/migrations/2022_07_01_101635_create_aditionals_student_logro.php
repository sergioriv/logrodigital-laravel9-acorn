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
        Schema::create('document_types', function (Blueprint $table) {
            $table->string('code', 5);
            $table->string('name');

            $table->primary('code');
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5);
            $table->string('name');
        });

        Schema::create('cities', function (Blueprint $table) { /* departments */
            $table->id();
            $table->unsignedBigInteger('department_id');
            $table->string('code', 5);
            $table->string('name');

            $table->foreign('department_id')
                    ->references('id')
                    ->on('departments')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });

        Schema::create('genders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('rhs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 5);
        });

        Schema::create('sisben', function (Blueprint $table) {
            $table->id();
            $table->string('name', 10);
        });

        Schema::create('ethnic_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
        });

        Schema::create('origin_schools', function (Blueprint $table) {
            $table->id();
            $table->string('name', 5);
        });

        Schema::create('health_managers', function (Blueprint $table) {
            $table->id();
            $table->string('type', 15);
            $table->string('code', 10);
            $table->string('nit', 15);
            $table->string('long_name');
            $table->string('name');
        });


        Schema::table('students', function (Blueprint $table) { /* document_types, cities, genders, rhs, sisben, ethnic_groups health_managers, origin_schools, headquarters, study_times, study_years */
            $table->string('first_name');
            $table->string('second_name')->nullable();
            $table->string('father_last_name');
            $table->string('mother_last_name')->nullable();
            $table->string('document_type_code', 5)->nullable();
            $table->string('document', 20)->unique()->nullable();
            $table->string('telephone', 20)->nullable();
            $table->string('institutional_email')->unique();


            /* residencia */
            $table->string('zone', 6)->nullable();
            $table->string('address', 100)->nullable();
            $table->unsignedBigInteger('residence_city_id')->nullable();


            /* adicionales */
            $table->unsignedBigInteger('health_manager_id')->nullable();
            $table->unsignedBigInteger('expedition_city_id')->nullable();
            $table->unsignedBigInteger('birth_city_id')->nullable();
            $table->date('birthdate')->nullable();
            $table->unsignedBigInteger('gender_id')->nullable();
            $table->unsignedBigInteger('rh_id')->nullable();
            $table->boolean('conflict_victim')->nullable();
            $table->unsignedTinyInteger('number_siblings')->nullable();
            $table->unsignedBigInteger('sisben_id')->nullable();
            $table->unsignedTinyInteger('social_stratum')->nullable();
            $table->boolean('lunch')->nullable();
            $table->boolean('refreshment')->nullable();
            $table->boolean('transport')->nullable();
            $table->boolean('ethnic_group_id')->nullable();
            $table->string('disability', 50);
            $table->unsignedBigInteger('origin_school_id')->nullable();
            $table->string('school_insurance', 100)->nullable();


            /* estados y hubicacion matrícula */
            $table->unsignedBigInteger('headquarters_id')->nullable();
            $table->unsignedBigInteger('study_time_id')->nullable();
            $table->unsignedBigInteger('study_year_id')->nullable();
            $table->date('enrolled_date')->nullable();
            $table->boolean('enrolled')->nullable(); // null is pre-registraed
            $table->enum('status', [
                'new',
                'repeat'
            ])->nullable();
            $table->boolean('inclusive')->nullable();
            $table->boolean('person_charge')->nullable();



            /* FOREIGN */
            $table->foreign('document_type_code')
                    ->references('code')
                    ->on('document_types')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreign('health_manager_id')
                    ->references('id')
                    ->on('health_managers')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreign('expedition_city_id')
                    ->references('id')
                    ->on('cities')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreign('birth_city_id')
                    ->references('id')
                    ->on('cities')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreign('gender_id')
                    ->references('id')
                    ->on('genders')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreign('rh_id')
                    ->references('id')
                    ->on('rhs')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreign('sisben_id')
                    ->references('id')
                    ->on('sisben')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreign('ethnic_group_id')
                    ->references('id')
                    ->on('ethnic_groups')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreign('residence_city_id')
                    ->references('id')
                    ->on('cities')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreign('origin_school_id')
                    ->references('id')
                    ->on('origin_schools')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            /* informacion basica */
            $table->dropColumn('first_name');
            $table->dropColumn('second_name');
            $table->dropColumn('father_last_name');
            $table->dropColumn('mother_last_name');
            $table->dropColumn('document_type_code');
            $table->dropColumn('document');
            $table->dropColumn('telephone');
            $table->dropColumn('institutional_email');
            $table->dropColumn('expedition_city_id');
            $table->dropColumn('birth_city_id');
            $table->dropColumn('birthdate');
            $table->dropColumn('gender_id');
            $table->dropColumn('rh_id');
            $table->dropColumn('number_siblings');

            /* lugar de domicilio */
            $table->dropColumn('zone');
            $table->dropColumn('address');
            $table->dropColumn('residence_city_id');
            $table->dropColumn('social_stratum');

            /* seguridad social */
            $table->dropColumn('health_manager_id');
            $table->dropColumn('sisben_id');
            $table->dropColumn('disability');
            $table->dropColumn('school_insurance');

            /* informacion complementaria */
            $table->dropColumn('ethnic_group_id');
            $table->dropColumn('conflict_victim');
            // $table->dropColumn('lunch');
            // $table->dropColumn('refreshment');
            // $table->dropColumn('transport');
            $table->dropColumn('origin_school_id');

            /* informacion pre-matricula */
            $table->dropColumn('headquarters_id');
            $table->dropColumn('study_time_id');
            $table->dropColumn('study_year_id');

            /* Inclusivo */
            $table->dropColumn('inclusive');

            $table->dropColumn('enrolled_date');
            $table->dropColumn('enrolled');
            $table->dropColumn('status');
            $table->dropColumn('inclusive');
            $table->dropColumn('person_charge');
        });

        Schema::dropIfExists('document_types');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('genders');
        Schema::dropIfExists('rhs');
        Schema::dropIfExists('origin_schools');


    }
};
