<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // add foreign constraints if columns exist
        if (Schema::hasTable('dosen')) {
            Schema::table('dosen', function (Blueprint $table) {
                if (! $this->hasForeign('dosen', 'dosen_id_user_foreign') && Schema::hasColumn('dosen', 'id_user')) {
                    $table->foreign('id_user')->references('id')->on('users')->onDelete('set null');
                }
            });
        }

        if (Schema::hasTable('dosen_pembimbing')) {
            Schema::table('dosen_pembimbing', function (Blueprint $table) {
                if (! $this->hasForeign('dosen_pembimbing', 'dosen_pembimbing_id_user_foreign') && Schema::hasColumn('dosen_pembimbing', 'id_user')) {
                    $table->foreign('id_user')->references('id')->on('users')->onDelete('set null');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('dosen')) {
            Schema::table('dosen', function (Blueprint $table) {
                $table->dropForeign(['id_user']);
            });
        }

        if (Schema::hasTable('dosen_pembimbing')) {
            Schema::table('dosen_pembimbing', function (Blueprint $table) {
                $table->dropForeign(['id_user']);
            });
        }
    }

    // helper: check if a foreign exists (best-effort)
    private function hasForeign(string $table, string $indexName): bool
    {
        try {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $sm->listTableForeignKeys($table);
            foreach ($indexes as $idx) {
                if ($idx->getName() === $indexName) return true;
            }
        } catch (\Exception $e) {
            // ignore
        }
        return false;
    }
};
