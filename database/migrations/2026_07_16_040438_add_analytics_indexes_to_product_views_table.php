<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_views', function (Blueprint $table) {
            $table->index('product_id');
            $table->index('created_at');
            $table->index(['product_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('product_views', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['product_id', 'created_at']);
        });
    }
};