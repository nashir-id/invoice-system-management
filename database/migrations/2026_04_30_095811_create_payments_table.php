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
        $table->foreignId('invoice_id')->unique()->constrained()->cascadeOnDelete();
        $table->unsignedBigInteger('recorded_by')->nullable();
        $table->enum('bank', ['mandiri', 'bca', 'seabank']);
        $table->decimal('amount', 15, 2);
        $table->date('paid_at');
        $table->text('notes')->nullable();
        $table->timestamps();

        $table->foreign('recorded_by')->references('id')->on('users')->nullOnDelete();
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
