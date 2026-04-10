@if($siteSettings && $siteSettings->top_bar_active)
<div id="top-bar" class="text-white text-[9px] md:text-xs text-center py-2 px-4 font-bold uppercase tracking-widest sticky top-0 z-[70]"
     style="background-color: {{ $siteSettings->top_bar_bg_color ?? '#4f46e5' }}; color: {{ $siteSettings->top_bar_text_color ?? '#ffffff' }};">
    {{ $siteSettings->top_bar_text ?? 'Besplatna dostava za sve porudžbine! 🚚' }}
</div>
@endif