<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $annonces = collect();

        if (!empty($cart)) {
            $ids = array_keys($cart);
            $annonces = Annonce::publiee()->with(['photos', 'user'])->whereIn('id', $ids)->get();
        }

        $grouped = $annonces->groupBy('devise');
        $totaux = $grouped->map(function ($group, $devise) use ($cart) {
            return $group->sum(fn($a) => $a->prix * ($cart[$a->id]['quantite'] ?? 1));
        });

        $count = array_sum(array_column($cart, 'quantite'));

        return view('cart.index', compact('annonces', 'cart', 'grouped', 'totaux', 'count'));
    }

    public function add(Request $request, Annonce $annonce)
    {
        if ($annonce->statut !== 'publiee') {
            return back()->with('error', 'Cette annonce n\'est pas disponible.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$annonce->id])) {
            $cart[$annonce->id]['quantite']++;
        } else {
            $cart[$annonce->id] = [
                'quantite' => 1,
                'ajoute_le' => now()->toDateTimeString(),
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')
            ->with('success', 'Annonce ajoutée au panier.');
    }

    public function remove(Request $request, Annonce $annonce)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$annonce->id])) {
            unset($cart[$annonce->id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Annonce retirée du panier.');
    }

    public function update(Request $request, Annonce $annonce)
    {
        $qte = max(1, (int) $request->input('qte', 1));

        $cart = session()->get('cart', []);

        if (isset($cart[$annonce->id])) {
            $cart[$annonce->id]['quantite'] = $qte;
            session()->put('cart', $cart);
        }

        return back();
    }

    public function clear()
    {
        session()->forget('cart');

        return redirect()->route('cart.index')
            ->with('success', 'Panier vidé.');
    }
}
