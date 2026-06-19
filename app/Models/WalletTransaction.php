<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $fillable = [
        'wallet_id', 'type', 'montant', 'devise', 'motif',
        'source_type', 'source_id',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function source()
    {
        return $this->morphTo();
    }
}
