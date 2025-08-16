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
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('product_id');
            $table->timestamps();
            $table->softDeletes()->index();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('restrict');
        });

        DB::statement("CREATE INDEX idx_customer_favorites_customer ON customer_favorites (customer_id) WHERE deleted_at IS NULL");
        DB::statement("CREATE INDEX idx_customer_favorites_product ON customer_favorites (product_id) WHERE deleted_at IS NULL");
        DB::statement("CREATE UNIQUE INDEX idx_customer_favorites_customer_product ON customer_favorites (customer_id, product_id) WHERE deleted_at IS NULL");
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('customer_favorites');
        Schema::enableForeignKeyConstraints();
    }
};