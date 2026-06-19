<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'annonce_id', 'acheteur_id', 'vendeur_id', 'dernier_message_at',
    ];

    protected $casts = [
        'dernier_message_at' => 'datetime',
    ];

    public function annonce()
    {
        return $this->belongsTo(Annonce::class);
    }

    public function acheteur()
    {
        return $this->belongsTo(User::class, 'acheteur_id');
    }

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'vendeur_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function dernierMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function interlocuteur(int $userId)
    {
        return $userId === $this->acheteur_id ? $this->vendeur : $this->acheteur;
    }

    public function scopePourUtilisateur($query, int $userId)
    {
        return $query->where('acheteur_id', $userId)
            ->orWhere('vendeur_id', $userId);
    }

    public function scopeNonLu($query, int $userId)
    {
        return $query->whereHas('messages', fn($q) => $q->where('user_id', '!=', $userId)->where('lu', false));
    }

    public function marquerLu(int $userId): void
    {
        $this->messages()
            ->where('user_id', '!=', $userId)
            ->where('lu', false)
            ->update(['lu' => true]);
    }
}
