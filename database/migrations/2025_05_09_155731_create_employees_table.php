<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('civil_id')->unique();
            $table->string('passport_number')->nullable();
            $table->string('gender');
            $table->string('marital_status');
            $table->string('phone')->nullable();
            $table->string('civil_id_front')->nullable();
            $table->string('civil_id_back')->nullable();
            $table->date('residency_start')->nullable();
            $table->date('residency_end');
            $table->string('job_title');
            $table->string('type'); // مقاول، مهندس، موظف
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
