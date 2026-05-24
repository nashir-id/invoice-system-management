<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('voucher_id')->nullable();
            $table->string('invoice_number')->unique();
            $table->enum('type', ['proforma', 'commercial', 'recurring']);
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->boolean('use_ppn')->default(false);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('ppn_amount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->enum('status', ['unpaid', 'paid', 'overdue'])->default('unpaid');
            $table->text('terms_conditions')->nullable();
            $table->string('estimation')->nullable();
            $table->text('notes')->nullable();
            $table->string('public_token', 64)->nullable()->unique();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};