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
    Schema::create('invoices', function (Blueprint $table) {
        $table->id();
        $table->foreignId('client_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('invoice_number')->unique();
        $table->enum('type', ['one_time', 'recurring']);
        $table->date('invoice_date');
        $table->date('due_date')->nullable();
        $table->decimal('subtotal', 15, 2);
        $table->decimal('discount', 15, 2)->default(0);
        $table->boolean('use_ppn')->default(true);
        $table->decimal('ppn_amount', 15, 2)->default(0);
        $table->decimal('total', 15, 2);
        $table->enum('status', ['UNPAID', 'PAID', 'OVERDUE'])->default('UNPAID');
        $table->text('terms')->nullable();
        $table->string('estimate')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
