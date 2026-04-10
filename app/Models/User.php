<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser; // DODAJ OVO
use Filament\Panel; // DODAJ OVO
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser // DODAJ "implements FilamentUser"
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * DODAJ OVU METODU:
     * Određuje ko može da uđe u admin panel na produkciji.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Za početak dozvoli svima koji su ulogovani (dok testiraš)
        // Kasnije možeš staviti: return str_ends_with($this->email, '@tvojdomen.com');
        return true;
    }
}
