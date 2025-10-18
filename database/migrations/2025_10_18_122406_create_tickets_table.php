<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('subject');
            $table->text('message');
            // Для SQLite надёжнее string, а не enum
            $table->string('status', 32)->default('new'); // new|in_progress|resolved
            $table->timestamp('manager_replied_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('tickets');
    }
};
