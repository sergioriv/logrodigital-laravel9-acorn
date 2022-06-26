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
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('first_name');
            $table->string('second_name')->nullable();
            $table->string('father_last_name');
            $table->string('mother_last_name')->nullable();
            $table->string('document', 20)->nullable();
            $table->enum('bonding_type', [
                'PROPIEDAD',
                'PERIODO DE PRUEBA',
                'PROVISIONAL VACANTE DEFINITIVA',
                'PROVISIONAL VACANTE TEMPORAL',
                'VACANTE TEMPORAL X INCAPACIDAD'])->nullable();
            $table->string('latest_degree')->nullable();
            $table->string('institutional_email');
            $table->string('personal_email')->nullable();
            $table->date('birthdate')->nullable();
            $table->boolean('whatsapp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn('document');
            $table->dropColumn('first_name');
            $table->dropColumn('second_name');
            $table->dropColumn('father_last_name');
            $table->dropColumn('mother_last_name');
            $table->dropColumn('bonding_type');
            $table->dropColumn('latest_degree');
            $table->dropColumn('institutional_email');
            $table->dropColumn('personal_email');
            $table->dropColumn('birthdate');
            $table->dropColumn('whatsapp');
        });
    }
};
