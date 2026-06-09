<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Profil Bisnis
            |--------------------------------------------------------------------------
            */

            $table->string('business_name')->nullable();
            $table->string('tagline')->nullable();

            $table->string('business_email')->nullable();
            $table->string('phone')->nullable();

            $table->string('website')->nullable();

            $table->string('logo')->nullable();

            $table->text('address')->nullable();

            $table->longText('description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Rekening Mandiri
            |--------------------------------------------------------------------------
            */

            $table->string('mandiri_name')->nullable();
            $table->string('mandiri_number')->nullable();
            $table->string('mandiri_holder')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Rekening BCA
            |--------------------------------------------------------------------------
            */

            $table->string('bca_name')->nullable();
            $table->string('bca_number')->nullable();
            $table->string('bca_holder')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Rekening SeaBank
            |--------------------------------------------------------------------------
            */

            $table->string('seabank_name')->nullable();
            $table->string('seabank_number')->nullable();
            $table->string('seabank_holder')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Metadata
            |--------------------------------------------------------------------------
            */

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};