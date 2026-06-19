<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Commande;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function recap()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $annonces = Annonce::publiee()->with(['photos', 'user'])->whereIn('id', array_keys($cart))->get();

        if ($annonces->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Aucune annonce disponible.');
        }

        $grouped = $annonces->groupBy('devise');
        $totaux = $grouped->map(fn($g, $d) => $g->sum(fn($a) => $a->prix * ($cart[$a->id]['quantite'] ?? 1)));

        return view('checkout.recap', compact('annonces', 'cart', 'grouped', 'totaux'));
    }

    public function confirmation(Commande $commande)
    {
        abort_unless($commande->acheteur_id === auth()->id(), 403);

        $commande->load(['annonces.photos', 'annonces.user', 'paiements']);

        return view('checkout.confirmation', compact('commande'));
    }

    public function historique()
    {
        $commandes = Commande::pourAcheteur(auth()->id())
            ->with(['annonces', 'paiements'])
            ->latest()
            ->paginate(10);

        return view('checkout.historique', compact('commandes'));
    }

    public function recevoir(Commande $commande)
    {
        abort_unless($commande->acheteur_id === auth()->id(), 403);
        abort_unless($commande->statut === 'expediee', 400, 'La commande doit être expédiée.');

        $commande->load('annonces');
        $commande->update(['statut' => 'livree']);

        foreach ($commande->annonces as $a) {
            $montant = $a->pivot->prix_unitaire * $a->pivot->quantite;
            $vendeur = $a->user;

            $vendeur->wallet->crediter(
                $montant,
                $a->pivot->devise,
                "Vente #{$commande->numero} — {$a->titre}",
                $commande
            );
        }

        return back()->with('success', 'Commande reçue. Le vendeur a été crédité.');
    }
}
