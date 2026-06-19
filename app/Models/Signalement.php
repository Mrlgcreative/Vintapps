<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Signalement extends Model
{
    protected $table = 'signalements';

    protected $fillable = [
        'annonce_id', 'signalé_par', 'motif', 'description', 'statut',
    ];

    public function annonce()
    {
        return $this->belongsTo(Annonce::class);
    }

    public function signaléPar()
    {
        return $this->belongsTo(User::class, 'signalé_par');
    }
}
