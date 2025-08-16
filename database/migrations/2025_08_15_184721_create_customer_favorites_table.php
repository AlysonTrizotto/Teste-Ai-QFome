<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('customer_favorites')) { return; }
        Schema::create('customer_favorites', function (Blueprint $table) {
            $table->id('id');
            $table->bigInteger('customer_id');
            $table->bigInteger('product_id');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['customer_id'], 'idx_customer_favorites_customer');
            $table->index(['product_id'], 'idx_customer_favorites_product');
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('customer_favorites');
        Schema::enableForeignKeyConstraints();
    }
};