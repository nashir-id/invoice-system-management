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
    Schema::create('clients', function (Blueprint $table) {
        $table->id();
        $table->string('company_name');        // ← ganti dari 'name'
        $table->string('pic_name')->nullable(); // ← tambah nullable
        $table->string('phone')->nullable();    // ← tambah nullable
        $table->string('email')->nullable();
        $table->string('website')->nullable();
        $table->text('address')->nullable();
        $table->text('notes')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
