<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {

            // $table->string('business_email')->nullable()->after('tagline');
            // $table->string('phone')->nullable()->after('business_email');
            // $table->string('website')->nullable()->after('phone');
            // $table->string('logo')->nullable()->after('website');
            // $table->text('address')->nullable()->after('logo');
            // $table->longText('description')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {

            $table->dropColumn([
                'business_email',
                'phone',
                'website',
                'logo',
                'address',
                'description'
            ]);
        });
    }
};