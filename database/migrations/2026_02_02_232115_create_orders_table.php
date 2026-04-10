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
  Schema::create('orders', function (Blueprint $table) {
$table->id();
$table->string('customer_name');
$table->string('customer_email');
$table->string('phone');
$table->string('address');
$table->decimal('subtotal', 10, 2);
$table->decimal('discount_amount', 10, 2)->default(0);
$table->decimal('total_amount', 10, 2);
$table->json('items');
$table->string('status')->default('novo');
$table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
