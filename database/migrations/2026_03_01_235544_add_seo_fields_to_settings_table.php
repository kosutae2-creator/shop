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
        Schema::table('settings', function (Blueprint $table) {
            // Provera za seo_title
            if (!Schema::hasColumn('settings', 'seo_title')) {
                $table->string('seo_title')->nullable();
            }
            
            // Provera za seo_description
            if (!Schema::hasColumn('settings', 'seo_description')) {
                $table->text('seo_description')->nullable();
            }
            
            // Provera za seo_keywords
            if (!Schema::hasColumn('settings', 'seo_keywords')) {
                $table->string('seo_keywords')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // Logika za brisanje kolona ako radis rollback
            if (Schema::hasColumn('settings', 'seo_title')) {
                $table->dropColumn('seo_title');
            }
            if (Schema::hasColumn('settings', 'seo_description')) {
                $table->dropColumn('seo_description');
            }
            if (Schema::hasColumn('settings', 'seo_keywords')) {
                $table->dropColumn('seo_keywords');
            }
        });
    }
};