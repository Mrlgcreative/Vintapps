<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSetting extends Model
{
    protected $fillable = [
        'titre', 'sous_titre', 'bouton_texte', 'bouton_lien',
        'image_fond', 'couleur_fond', 'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];
}
