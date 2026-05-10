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
    Schema::create('recurring_templates', function (Blueprint $table) {
        $table->id();
        $table->foreignId('client_id')->constrained()->cascadeOnDelete();
        $table->string('service_name');
        $table->decimal('amount', 15, 2);
        $table->enum('mode', ['auto', 'manual']);
        $table->integer('generate_day'); // tanggal tiap bulan
        $table->enum('status', ['active', 'paused'])->default('active');
        $table->timestamps();
     });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurring_templates');
    }
};
