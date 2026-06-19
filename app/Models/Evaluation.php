<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'acheteur_id', 'vendeur_id', 'annonce_id', 'note', 'commentaire',
    ];

    protected $casts = [
        'note' => 'integer',
    ];

    public function acheteur()
    {
        return $this->belongsTo(User::class, 'acheteur_id');
    }

    public function vendeur()
    {
        return $this->belongsTo(User::class, 'vendeur_id');
    }

    public function annonce()
    {
        return $this->belongsTo(Annonce::class);
    }
}
