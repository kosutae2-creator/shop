<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Podešavanja Prodavnice';
    protected static ?string $navigationGroup = 'Admin';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')
                    ->tabs([
                        // TAB 1: IDENTITET I DIZAJN
                        Forms\Components\Tabs\Tab::make('Brending i Boje')
                            ->icon('heroicon-o-paint-brush')
                            ->schema([
                                Forms\Components\TextInput::make('site_name')
                                    ->label('Naziv Sajta')
                                    ->required(),
                                Forms\Components\FileUpload::make('logo_path')
                                    ->label('Logo')
                                    ->image()
                                    ->directory('site'),
                                Forms\Components\ColorPicker::make('primary_color')
                                    ->label('Glavna Boja (Dugmići, Linkovi)')
                                    ->default('#4f46e5'),
                                Forms\Components\ColorPicker::make('secondary_color')
                                    ->label('Sporedna Boja')
                                    ->default('#1e293b'),
                            ])->columns(2),

                        // TAB 2: TOP BAR
                        Forms\Components\Tabs\Tab::make('Gornja Traka (Top Bar)')
                            ->icon('heroicon-o-megaphone')
                            ->schema([
                                Forms\Components\Toggle::make('top_bar_active')
                                    ->label('Aktiviraj Top Bar'),
                                Forms\Components\TextInput::make('top_bar_text')
                                    ->label('Tekst obaveštenja'),
                                Forms\Components\ColorPicker::make('top_bar_bg_color')
                                    ->label('Boja Pozadine Trake'),
                                Forms\Components\ColorPicker::make('top_bar_text_color')
                                    ->label('Boja Teksta Trake'),
                            ])->columns(2),

                        // TAB 3: BANER (HERO)
                        Forms\Components\Tabs\Tab::make('Glavni Baner')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Forms\Components\Toggle::make('banner_active')
                                    ->label('Prikaži Baner'),
                                Forms\Components\TextInput::make('banner_title')
                                    ->label('Naslov Banera'),
                                Forms\Components\Textarea::make('banner_description')
                                    ->label('Opis Banera (Ispod naslova)'),
                                Forms\Components\TextInput::make('banner_button_text')
                                    ->label('Tekst na dugmetu'),
                                Forms\Components\FileUpload::make('banner_image')
                                    ->label('Slika Banera')
                                    ->image()
                                    ->directory('site'),
                            ]),

                        // TAB 4: KONTAKT I MREŽE
                        Forms\Components\Tabs\Tab::make('Kontakt i Mreže')
                            ->icon('heroicon-o-chat-bubble-left-right')
                            ->schema([
                                Forms\Components\TextInput::make('contact_email')->email(),
                                Forms\Components\TextInput::make('contact_phone')->label('Telefon'),
                                Forms\Components\TextInput::make('contact_address')->label('Adresa'),
                                Forms\Components\TextInput::make('fb_link')->label('Facebook Link')->url(),
                                Forms\Components\TextInput::make('ig_link')->label('Instagram Link')->url(),
                                Forms\Components\TextInput::make('tiktok_link')->label('TikTok Link')->url(),
                                Forms\Components\TextInput::make('youtube_link')->label('YouTube Link')->url(),
                            ])->columns(2),

                        // TAB 5: FOOTER I DODATNE INFORMACIJE
                        Forms\Components\Tabs\Tab::make('Footer Podešavanja')
                            ->icon('heroicon-o-queue-list')
                            ->schema([
                                Forms\Components\TextInput::make('footer_about')
                                    ->label('Kratak opis u footeru')
                                    ->placeholder('Npr. Premium Shopping Experience')
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('copyright_text')
                                    ->label('Copyright Tekst')
                                    ->placeholder('© 2026 Vision. All Rights Reserved.')
                                    ->columnSpanFull(),
                            ]),

                        // TAB 6: SEO OPTIMIZACIJA
                        Forms\Components\Tabs\Tab::make('SEO Optimizacija')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                Forms\Components\TextInput::make('seo_title')
                                    ->label('Meta Naslov (SEO Title)')
                                    ->helperText('Ovo se pojavljuje u tabu browsera i na Google-u.')
                                    ->placeholder('Vision - Premium Online Prodavnica'),
                                Forms\Components\Textarea::make('seo_description')
                                    ->label('Meta Opis (Meta Description)')
                                    ->helperText('Kratak opis sajta koji Google prikazuje.')
                                    ->rows(3),
                                Forms\Components\TextInput::make('seo_keywords')
                                    ->label('Ključne Reči')
                                    ->placeholder('odeća, online prodaja, premium brendovi')
                                    ->helperText('Odvojite reči zarezom.'),
                            ]),
                    ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_path')
                    ->label('Logo'),
                Tables\Columns\TextColumn::make('site_name')
                    ->label('Naziv Prodavnice')
                    ->searchable(),
                Tables\Columns\ColorColumn::make('primary_color')
                    ->label('Glavna Boja'),
                Tables\Columns\IconColumn::make('top_bar_active')
                    ->label('Top Bar')
                    ->boolean(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}