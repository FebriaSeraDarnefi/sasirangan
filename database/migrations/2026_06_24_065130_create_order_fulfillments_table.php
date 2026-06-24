<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_fulfillments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->foreignId('umkm_id')
                ->constrained('umkms')
                ->restrictOnDelete();

            $table->enum('status', [
                'processing',
                'packed',
                'shipped',
                'completed',
                'cancelled',
            ])->default('processing');

            $table->string('courier')->nullable();
            $table->string('tracking_number')->nullable();
            $table->text('notes')->nullable();

            $table->timestamp('packed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            $table->unique([
                'order_id',
                'umkm_id',
            ]);

            $table->index([
                'umkm_id',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_fulfillments');
    }
};
