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
            // Proveravamo svaku kolonu pre dodavanja da izbegnemo "Duplicate column" grešku
            if (!Schema::hasColumn('settings', 'footer_about')) {
                $table->string('footer_about')->nullable();
            }
            if (!Schema::hasColumn('settings', 'copyright_text')) {
                $table->string('copyright_text')->nullable();
            }
            if (!Schema::hasColumn('settings', 'tiktok_link')) {
                $table->string('tiktok_link')->nullable();
            }
            if (!Schema::hasColumn('settings', 'youtube_link')) {
                $table->string('youtube_link')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['footer_about', 'copyright_text', 'tiktok_link', 'youtube_link']);
        });
    }
};