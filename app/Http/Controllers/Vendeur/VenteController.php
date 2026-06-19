<?php

namespace App\Http\Controllers\Vendeur;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\Request;

class VenteController extends Controller
{
    public function index()
    {
        $query = Commande::whereHas('annonces', fn($q) => $q->where('user_id', auth()->id()))
            ->with(['annonces' => fn($q) => $q->where('user_id', auth()->id()), 'annonces.photos', 'acheteur'])
            ->latest();

        $statut = request('statut');
        if ($statut) {
            $query->where('statut', $statut);
        }

        $ventes = $query->paginate(10);

        return view('vendeur.ventes.index', compact('ventes'));
    }

    public function expedier(Commande $commande)
    {
        abort_unless($commande->annonces()->where('user_id', auth()->id())->exists(), 403);

        $commande->update(['statut' => 'expediee']);

        return back()->with('success', 'Commande marquée comme expédiée.');
    }
}
