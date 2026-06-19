<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Commande extends Model
{
    protected $fillable = [
        'acheteur_id', 'numero', 'statut', 'notes',
    ];

    protected static function booted(): void
    {
        static::creating(function (Commande $cmd) {
            if (empty($cmd->numero)) {
                $cmd->numero = 'CMD-' . strtoupper(Str::random(8));
            }
        });
    }

    public function acheteur()
    {
        return $this->belongsTo(User::class, 'acheteur_id');
    }

    public function annonces()
    {
        return $this->belongsToMany(Annonce::class, 'commande_annonce')
            ->withPivot(['prix_unitaire', 'devise', 'quantite']);
    }

    public function scopePourAcheteur($query, int $userId)
    {
        return $query->where('acheteur_id', $userId);
    }

    public function totalParDevise(): array
    {
        $totaux = [];
        foreach ($this->annonces as $a) {
            $d = $a->pivot->devise;
            $totaux[$d] = ($totaux[$d] ?? 0) + ($a->pivot->prix_unitaire * $a->pivot->quantite);
        }
        return $totaux;
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function paiementValide()
    {
        return $this->paiements()->where('statut', 'paye')->first();
    }
}
