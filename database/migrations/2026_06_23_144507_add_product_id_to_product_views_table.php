<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('product_views', 'product_id')) {
            Schema::table('product_views', function (Blueprint $table) {
                $table->foreignId('product_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('products')
                    ->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('product_views', 'product_id')) {
            Schema::table('product_views', function (Blueprint $table) {
                $table->dropConstrainedForeignId('product_id');
            });
        }
    }
};
