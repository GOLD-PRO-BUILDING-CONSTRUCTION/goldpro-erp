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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            // العلاقة مع العميل
            $table->foreignId('client_id')->constrained()->onDelete('cascade');

            // الحقول المطلوبة
            $table->string('contract_number');            // رقم العقد
            $table->string('project_number');             // رقم المشروع / القسيمة
            $table->string('contract_type');              // نوع العقد
            $table->decimal('contract_value', 15, 3);     // قيمة العقد
            $table->string('address');                    // العنوان

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
