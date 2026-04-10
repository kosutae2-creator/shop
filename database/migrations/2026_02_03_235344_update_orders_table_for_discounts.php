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
        Schema::table('orders', function (Blueprint $table) {
            // 1. Čišćenje starih/pogrešnih kolona ako postoje
            if (Schema::hasColumn('orders', 'total_price')) {
                $table->dropColumn('total_price');
            }

            // 2. Dodavanje novih kolona sa proverom postojanja
            if (!Schema::hasColumn('orders', 'subtotal')) {
                $table->decimal('subtotal', 12, 2)->after('address')->default(0);
            }
            
            if (!Schema::hasColumn('orders', 'discount_amount')) {
                $table->decimal('discount_amount', 12, 2)->after('subtotal')->default(0);
            }
            
            if (!Schema::hasColumn('orders', 'total_amount')) {
                $table->decimal('total_amount', 12, 2)->after('discount_amount')->default(0);
            }
            
            if (!Schema::hasColumn('orders', 'coupon_code')) {
                $table->string('coupon_code')->nullable()->after('total_amount');
            }
            
            if (!Schema::hasColumn('orders', 'items')) {
                $table->json('items')->after('coupon_code')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = ['subtotal', 'discount_amount', 'total_amount', 'coupon_code', 'items'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};