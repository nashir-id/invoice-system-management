<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            $table->string('transfer_proof')->nullable()->after('notes');

            $table->enum('verification_status', [
                'pending',
                'approved',
                'rejected'
            ])->default('pending')->after('transfer_proof');

            $table->unsignedBigInteger('verified_by')
                ->nullable()
                ->after('verification_status');

            $table->timestamp('verified_at')
                ->nullable()
                ->after('verified_by');

            $table->text('verification_note')
                ->nullable()
                ->after('verified_at');

            $table->foreign('verified_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            $table->dropForeign(['verified_by']);

            $table->dropColumn([
                'transfer_proof',
                'verification_status',
                'verified_by',
                'verified_at',
                'verification_note'
            ]);
        });
    }
};