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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('sku');
            $table->string('barcode')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->softDeletes();
            
            $table->timestamps();

            $table->unique(['company_id', 'sku']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
