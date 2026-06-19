<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Categorie extends Model
{
    protected $fillable = [
        'libelle', 'slug', 'description', 'icone', 'couleur', 'ordre', 'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Categorie $cat) {
            if (empty($cat->slug)) {
                $cat->slug = Str::slug($cat->libelle);
            }
        });
    }

    public function annonces()
    {
        return $this->hasMany(Annonce::class);
    }
}
