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
        Schema::create('t_sales', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 15)->nullable()->index('t_s_k');
            $table->dateTime('tgl')->nullable()->index('t_s_tg');
            $table->foreignId('cust_id')->nullable()->index('t_s_ci');
            $table->decimal('subtotal', 28, 2)->nullable();
            $table->decimal('diskon', 28, 2)->nullable();
            $table->decimal('ongkir', 28, 2)->nullable();
            $table->decimal('total_bayar', 28, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_sales');
    }
};
