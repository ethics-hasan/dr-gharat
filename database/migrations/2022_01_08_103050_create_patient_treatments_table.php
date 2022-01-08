<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_treatments', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('patient_id')
                ->constrained()
                ->onDelete('cascade');
                  
            $table->foreignId('xray_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('sonography_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('blood_test_id')->nullable()->constrained()->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_treatments');
    }
}
