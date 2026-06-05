<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("UPDATE invoices SET type = 'one_time' WHERE type IN ('proforma', 'commercial')");
        DB::statement("ALTER TABLE invoices MODIFY type ENUM('one_time', 'recurring') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE invoices MODIFY type ENUM('proforma', 'commercial', 'recurring') NOT NULL");
        DB::statement("UPDATE invoices SET type = 'commercial' WHERE type = 'one_time'");
    }
};
