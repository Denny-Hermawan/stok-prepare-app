<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prepare_logs', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->integer('shift')->comment('1 atau 2');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->integer('stok_awal')->default(0);
            $table->integer('stok_prepare')->default(0);
            $table->integer('stok_terpakai')->default(0);
            $table->integer('stok_sisa')->default(0);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Unique constraint untuk tanggal + shift + item
            $table->unique(['tanggal', 'shift', 'item_id'], 'unique_prepare_entry');

            // Indexes untuk performa
            $table->index(['tanggal', 'shift']);
            $table->index('item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prepare_logs');
    }
};
