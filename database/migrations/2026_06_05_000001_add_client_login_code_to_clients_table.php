<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('client_login_code', 32)->nullable()->unique()->after('notes');
        });

        DB::table('clients')
            ->whereNull('client_login_code')
            ->orderBy('id')
            ->get(['id'])
            ->each(function ($client) {
                do {
                    $code = 'CLI-' . strtoupper(Str::random(8));
                } while (DB::table('clients')->where('client_login_code', $code)->exists());

                DB::table('clients')
                    ->where('id', $client->id)
                    ->update(['client_login_code' => $code]);
            });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropUnique(['client_login_code']);
            $table->dropColumn('client_login_code');
        });
    }
};
