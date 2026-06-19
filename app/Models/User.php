<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
        'boutique_name',
        'bio',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
        ];
    }

    public function estAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function estVendeur(): bool
    {
        return $this->role === 'vendeur';
    }

    public function estAcheteur(): bool
    {
        return $this->role === 'acheteur';
    }

    public function annonces()
    {
        return $this->hasMany(Annonce::class);
    }

    public function favoris()
    {
        return $this->belongsToMany(Annonce::class, 'favoris')->withTimestamps();
    }

    public function evaluationsRecues()
    {
        return $this->hasMany(Evaluation::class, 'vendeur_id');
    }

    public function evaluationsDonnees()
    {
        return $this->hasMany(Evaluation::class, 'acheteur_id');
    }

    public function noteMoyenne(): float
    {
        return round($this->evaluationsRecues()->avg('note') ?? 0, 1);
    }

    public function estDansFavoris(Annonce $annonce): bool
    {
        return $this->favoris()->where('annonce_id', $annonce->id)->exists();
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function creerWallet(): Wallet
    {
        return $this->wallet()->create([
            'balance_usd' => 0,
            'balance_cdf' => 0,
        ]);
    }

    protected static function booted(): void
    {
        static::created(function (User $user) {
            $user->creerWallet();
        });
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class, 'acheteur_id');
    }

    public function ventes()
    {
        return $this->hasManyThrough(Commande::class, Annonce::class, 'user_id', 'id', 'id', 'commande_id')
            ->join('commande_annonce', 'commandes.id', '=', 'commande_annonce.commande_id')
            ->whereColumn('annonces.user_id', '=', $this->id)
            ->distinct();
    }
}
