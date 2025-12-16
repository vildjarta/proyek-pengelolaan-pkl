<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // NOTE: Foreign keys for id_user in dosen and dosen_pembimbing tables
        // are already defined in original migrations:
        // - 2025_09_23_235959_create_dosen_table.php
        // - 2025_09_24_000000_create_dosen_pembimbing_table.php
        // This migration is a no-op to avoid "Duplicate key" errors (errno 1005)
    }

    public function down(): void
    {
        // This migration is a no-op, nothing to rollback
    }
};
