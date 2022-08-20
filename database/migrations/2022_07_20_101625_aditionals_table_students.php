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
        Schema::table('students', function (Blueprint $table) {
            $table->string('favorite_subjects')->nullable();
            $table->string('most_difficult_subjects')->nullable();
            $table->boolean('insomnia')->nullable();
            $table->boolean('colic')->nullable();
            $table->boolean('biting_nails')->nullable();
            $table->boolean('sleep_talk')->nullable();
            $table->boolean('nightmares')->nullable();
            $table->boolean('seizures')->nullable();
            $table->boolean('physical_abuse')->nullable();
            $table->boolean('pee_at_night')->nullable();
            $table->boolean('hear_voices')->nullable();
            $table->boolean('fever')->nullable();
            $table->boolean('fears_phobias')->nullable();
            $table->boolean('drug_consumption')->nullable();
            $table->boolean('head_blows')->nullable();
            $table->boolean('desire_to_die')->nullable();
            $table->boolean('see_strange_things')->nullable();
            $table->boolean('learning_problems')->nullable();
            $table->boolean('dizziness_fainting')->nullable();
            $table->boolean('school_repetition')->nullable();
            $table->boolean('accidents')->nullable();
            $table->boolean('asthma')->nullable();
            $table->boolean('suicide_attempts')->nullable();
            $table->boolean('constipation')->nullable();
            $table->boolean('stammering')->nullable();
            $table->boolean('hands_sweating')->nullable();
            $table->boolean('sleepwalking')->nullable();
            $table->boolean('nervous_tics')->nullable();

            $table->boolean('wizard_documents')->nullable();
            $table->boolean('wizard_person_charge')->nullable();
            $table->boolean('wizard_personal_info')->nullable();
            $table->boolean('wizard_complete')->nullable();

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
