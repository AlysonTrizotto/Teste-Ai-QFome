<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('customers')) { return; }
        Schema::create('customers', function (Blueprint $table) {
            $table->id('id');
            $table->string('name', 255);
            $table->string('email');
            $table->timestamps();
            $table->softDeletes()->index();
        });

        DB::statement("CREATE INDEX idx_customers_name ON customers (name) WHERE deleted_at IS NULL");
        DB::statement("CREATE UNIQUE INDEX idx_customers_email ON customers (lower(email)) WHERE deleted_at IS NULL");
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('customers');
        Schema::enableForeignKeyConstraints();
    }
};