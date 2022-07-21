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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
        });

        Schema::create('dwelling_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
        });

        Schema::create('disabilities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
        });

        Schema::create('ICBF_protection_measures', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
        });

        Schema::create('linkage_processes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
        });

        Schema::create('religions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
        });

        Schema::create('economic_dependences', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
        });


        Schema::table('students', function (Blueprint $table) {
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('dwelling_type_id')->nullable();
            $table->string('neighborhood', 100)->nullable();
            $table->boolean('electrical_energy')->nullable();
            $table->boolean('natural_gas')->nullable();
            $table->boolean('sewage_system')->nullable();
            $table->boolean('aqueduct')->nullable();
            $table->boolean('internet')->nullable();
            $table->boolean('lives_with_father')->nullable();
            $table->boolean('lives_with_mother')->nullable();
            $table->boolean('lives_with_siblings')->nullable();
            $table->boolean('lives_with_other_relatives')->nullable();
            $table->unsignedBigInteger('disability_id')->nullable();
            $table->unsignedBigInteger('ICBF_protection_measure_id')->nullable();
            $table->boolean('foundation_beneficiary')->nullable();
            $table->unsignedBigInteger('linked_to_process_id')->nullable();
            $table->unsignedBigInteger('religion_id')->nullable();
            $table->unsignedBigInteger('economic_dependence_id')->nullable();
            $table->boolean('plays_sports')->nullable();
            $table->string('freetime_activity')->nullable();
            $table->string('allergies')->nullable();
            $table->string('medicines')->nullable();


            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('dwelling_type_id')
                ->references('id')
                ->on('dwelling_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('disability_id')
                ->references('id')
                ->on('disabilities')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('ICBF_protection_measure_id')
                ->references('id')
                ->on('ICBF_protection_measures')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('linked_to_process_id')
                ->references('id')
                ->on('linkage_processes')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('religion_id')
                ->references('id')
                ->on('religions')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('economic_dependence_id')
                ->references('id')
                ->on('economic_dependences')
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
        //
    }
};
