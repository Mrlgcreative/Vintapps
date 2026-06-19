<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'user_id', 'balance_usd', 'balance_cdf',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function crediter(float $montant, string $devise, string $motif, Model $source): self
    {
        $col = $devise === 'CDF' ? 'balance_cdf' : 'balance_usd';
        $this->increment($col, $montant);

        $this->transactions()->create([
            'type' => 'credit',
            'montant' => $montant,
            'devise' => $devise,
            'motif' => $motif,
            'source_type' => $source->getMorphClass(),
            'source_id' => $source->id,
        ]);

        return $this;
    }

    public function debiter(float $montant, string $devise, string $motif, Model $source): self
    {
        $col = $devise === 'CDF' ? 'balance_cdf' : 'balance_usd';
        $this->decrement($col, $montant);

        $this->transactions()->create([
            'type' => 'debit',
            'montant' => $montant,
            'devise' => $devise,
            'motif' => $motif,
            'source_type' => $source->getMorphClass(),
            'source_id' => $source->id,
        ]);

        return $this;
    }
}
