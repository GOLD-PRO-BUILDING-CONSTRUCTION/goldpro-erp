<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationsTable extends Migration
{
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade'); //العميل
            $table->date('date'); // تاريخ العرض
            $table->string('plote_number'); // رقم القسيمة / المشروع
            $table->string('quotation_number'); // رقم عرض السعر
            $table->decimal('unit_value', 10, 2); // قيمة العرض حسب الوحدة
            $table->string('unit_of_measurement'); // وحدة القياس
            $table->string('service_type'); // نوع الخدمة
            $table->string('file')->nullable(); // الملف
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quotations');
    }
}
