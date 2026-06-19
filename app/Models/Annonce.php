<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Annonce extends Model
{
    protected $fillable = [
        'user_id', 'categorie_id', 'titre', 'description',
        'prix', 'devise', 'etat', 'statut', 'vues',
    ];

    protected $casts = [
        'prix' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function signalements()
    {
        return $this->hasMany(Signalement::class);
    }

    public function photoCouverture()
    {
        return $this->hasOne(Photo::class)->where('est_couverture', true);
    }

    public function getUrlCouvertureAttribute()
    {
        $photo = $this->photoCouverture ?? $this->photos()->first();
        return $photo ? Storage::url($photo->chemin_fichier) : null;
    }

    public function scopePubliee($query)
    {
        return $query->where('statut', 'publiee');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
