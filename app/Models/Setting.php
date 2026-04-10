<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name', 
        'logo_path', 
        'favicon_path',
        'primary_color', 
        'secondary_color',
        'top_bar_active', 
        'top_bar_text', 
        'top_bar_bg_color', 
        'top_bar_text_color',
        'banner_active', 
        'banner_title', 
        'banner_description', 
        'banner_image', 
        'banner_button_text',
        'contact_email', 
        'contact_phone', 
        'contact_address',
        'fb_link', 
        'ig_link', 
        'tiktok_link',
        'youtube_link',   // DODATO
        'footer_about',   // DODATO
        'copyright_text',  // DODATO
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];
}