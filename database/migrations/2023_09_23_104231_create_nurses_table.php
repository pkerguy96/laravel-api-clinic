<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nurses', function (Blueprint $table) {
            $table->id();
            $table->string('doctor_id');
            $table->string('nom');
            $table->string('prenom');
            $table->string('cin')->unique();;
            $table->date('date');
            $table->text('address');
            $table->enum('sex', ['male', 'female']);
            $table->string('phone_number', 20)->unique();;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nurses');
    }
};
