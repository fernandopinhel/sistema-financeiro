<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\RecurringTemplate;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'google_id',
        'microsoft_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Sobrescreve a notificação de reset de senha para usar a versão em português.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }

    // Relacionamento com templates recorrentes
    public function recurringTemplates()
    {
        return $this->hasMany(RecurringTemplate::class);
    }

    public function getAvatarUrlAttribute(): ?string
    {
        if (!$this->avatar) return null;
        return str_starts_with($this->avatar, 'http') ? $this->avatar : asset('storage/' . $this->avatar);
    }
}
