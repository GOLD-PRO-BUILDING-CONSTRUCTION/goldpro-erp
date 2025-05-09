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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('payment_number'); // رقم الدفعة التسلسلي
            $table->date('payment_date');
            $table->enum('status', ['مدفوع', 'غير مدفوع'])->default('غير مدفوع');
            $table->decimal('amount', 12, 2);
            $table->decimal('percentage', 5, 2); // نسبة من قيمة المشروع
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
