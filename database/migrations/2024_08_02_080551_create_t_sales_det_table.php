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
        Schema::create('t_sales_det', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_id')->nullable()->index('t_s_d_si');
            $table->foreignId('barang_id')->nullable()->index('t_s_d_bi');
            $table->decimal('harga_bandrol', 28, 2)->nullable();
            $table->integer('qty')->nullable();
            $table->decimal('diskon_pct', 28, 2)->nullable();
            $table->decimal('diskon_nilai', 28, 2)->nullable();
            $table->decimal('harga_diskon', 28, 2)->nullable();
            $table->decimal('total', 28, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_sales_det');
    }
};
