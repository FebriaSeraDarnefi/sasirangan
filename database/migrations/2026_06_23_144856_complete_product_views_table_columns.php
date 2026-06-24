<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('product_views', 'user_id')) {
            Schema::table('product_views', function (Blueprint $table) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('product_id')
                    ->constrained('users')
                    ->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('product_views', 'session_identifier')) {
            Schema::table('product_views', function (Blueprint $table) {
                $table->string('session_identifier')
                    ->nullable()
                    ->after('user_id')
                    ->index();
            });
        }

        if (! Schema::hasColumn('product_views', 'viewed_at')) {
            Schema::table('product_views', function (Blueprint $table) {
                $table->timestamp('viewed_at')
                    ->nullable()
                    ->after('session_identifier')
                    ->index();
            });
        }

        if (! Schema::hasColumn('product_views', 'created_at')) {
            Schema::table('product_views', function (Blueprint $table) {
                $table->timestamp('created_at')->nullable();
            });
        }

        if (! Schema::hasColumn('product_views', 'updated_at')) {
            Schema::table('product_views', function (Blueprint $table) {
                $table->timestamp('updated_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        // Migration ini khusus memperbaiki struktur tabel yang tidak lengkap.
        // Tidak dibuat rollback destruktif agar kolom lama tidak ikut terhapus.
    }
};
