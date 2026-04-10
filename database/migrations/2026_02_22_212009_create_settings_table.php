<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('settings', function (Blueprint $table) {
        $table->id();
        
        // 1. OSNOVNI IDENTITET
        $table->string('site_name')->default('PROSHOP');
        $table->string('logo_path')->nullable();
        $table->string('favicon_path')->nullable();
        
        // 2. DIZAJN I BOJE
        $table->string('primary_color')->default('#4f46e5');
        $table->string('secondary_color')->default('#1e293b');
        
        // 3. TOP BAR
        $table->boolean('top_bar_active')->default(true);
        $table->string('top_bar_text')->nullable();
        $table->string('top_bar_bg_color')->default('#4f46e5');
        $table->string('top_bar_text_color')->default('#ffffff');

        // 4. BANER (HERO) - USKLAĐENO SA ADMINOM
        $table->boolean('banner_active')->default(true); // Dodato
        $table->string('banner_image')->nullable();      // Promenjeno ime (izbačeno static_)
        $table->string('banner_title')->nullable();      // Promenjeno ime
        $table->text('banner_description')->nullable();  // Promenjeno ime
        $table->string('banner_button_text')->default('Kupuj odmah'); // Dodato

        // 5. KONTAKT I MREŽE
        $table->string('contact_email')->nullable();
        $table->string('contact_phone')->nullable();
        $table->string('contact_address')->nullable();
        $table->string('fb_link')->nullable();
        $table->string('ig_link')->nullable();
        $table->string('tiktok_link')->nullable();

        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};