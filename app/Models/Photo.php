<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = [
        'annonce_id', 'chemin_fichier', 'est_couverture', 'texte_alt',
    ];

    protected $casts = [
        'est_couverture' => 'boolean',
    ];

    public function annonce()
    {
        return $this->belongsTo(Annonce::class);
    }
}
