<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('paid'); // paid/refunded/etc
            $table->unsignedInteger('total_cents')->default(0);
            $table->timestamp('ordered_at')->useCurrent();
            $table->timestamps();

            // For reporting queries
            $table->index(['user_id', 'ordered_at']);
            $table->index(['ordered_at', 'status']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
