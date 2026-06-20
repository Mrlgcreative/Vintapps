<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Commande;
use App\Models\Paiement;
use App\Services\MaishaPay;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaiementController extends Controller
{
    public function payer(Request $request, MaishaPay $maishaPay)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $annonces = Annonce::publiee()->whereIn('id', array_keys($cart))->get();
        if ($annonces->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Aucune annonce disponible.');
        }

        $validated = $request->validate([
            'telephone' => 'required|string|max:20',
            'operateur' => 'required|in:mpesa,airtel,orange',
            'notes' => 'nullable|string|max:500',
        ]);

        $commande = Commande::create([
            'acheteur_id' => auth()->id(),
            'statut' => 'en_attente',
            'notes' => $validated['notes'],
        ]);

        foreach ($annonces as $a) {
            $qte = $cart[$a->id]['quantite'] ?? 1;
            $commande->annonces()->attach($a->id, [
                'prix_unitaire' => $a->prix,
                'devise' => $a->devise,
                'quantite' => $qte,
            ]);
        }

        $grouped = $annonces->groupBy('devise');
        $total = $grouped->sum(fn($g) => $g->sum(fn($a) => $a->prix * ($cart[$a->id]['quantite'] ?? 1)));
        $devise = $grouped->keys()->first() ?? 'USD';

        $minCdf = 100;
        $minUsd = 1;
        if (($devise === 'CDF' && $total < $minCdf) || ($devise === 'USD' && $total < $minUsd)) {
            return redirect()->route('cart.index')
                ->with('error', "Le montant minimum pour un paiement Mobile Money est de {$minCdf} FC ou {$minUsd} USD.");
        }

        $transactionId = 'VT-' . strtoupper(Str::random(14));

        $message = 'Commande créée. ';
        $error = null;

        if ($maishaPay->isConfigured()) {
            $result = $maishaPay->initiatePayment([
                'transaction_id' => $transactionId,
                'phone' => $validated['telephone'],
                'operator' => strtoupper($validated['operateur']),
                'amount' => $total,
                'currency' => $devise,
                'user_name' => auth()->user()->name,
                'customer_email' => auth()->user()->email,
            ]);

            $maishapayId = $result['maishapay_id'] ?? null;

            if ($result['success']) {
                $message .= 'Confirmez sur votre téléphone.';
            } else {
                $error = $result['message'] ?? 'Erreur de paiement';
                $message .= 'Problème lors de l\'initiation du paiement. Vous pouvez réessayer.';
                if (isset($result['data']['errors'])) {
                    $details = is_array($result['data']['errors'])
                        ? implode(', ', array_map(fn($v) => is_array($v) ? implode(', ', $v) : $v, $result['data']['errors']))
                        : $result['data']['errors'];
                    $message .= ' Détail: ' . $details;
                }
            }
        } else {
            $maishapayId = null;
            $message .= 'Confirmez le paiement Mobile Money.';
        }

        session()->flash('maishapay_error', $error);

        $paiement = Paiement::create([
            'commande_id' => $commande->id,
            'methode' => 'mobile_money',
            'operateur' => $validated['operateur'],
            'telephone' => $validated['telephone'],
            'reference' => $transactionId,
            'reference_externe' => $maishapayId,
            'montant' => $total,
            'devise' => $devise,
            'statut' => 'en_attente',
        ]);

        session()->forget('cart');

        if ($error) {
            return redirect()->route('checkout.paiement', [$commande, $paiement])
                ->with('warning', $message);
        }

        return redirect()->route('checkout.paiement', [$commande, $paiement])
            ->with('success', $message);
    }

    public function paiement(Commande $commande, Paiement $paiement)
    {
        abort_unless($commande->acheteur_id === auth()->id(), 403);
        abort_unless($paiement->commande_id === $commande->id, 404);
        abort_unless($paiement->statut === 'en_attente', 400, 'Paiement déjà traité.');

        $commande->load(['annonces.photos', 'annonces.user']);

        return view('checkout.paiement', compact('commande', 'paiement'));
    }

    public function confirmerPaiement(Commande $commande, Paiement $paiement, MaishaPay $maishaPay)
    {
        abort_unless($commande->acheteur_id === auth()->id(), 403);
        abort_unless($paiement->commande_id === $commande->id, 404);
        abort_unless($paiement->statut === 'en_attente', 400, 'Paiement déjà traité.');

        if ($maishaPay->isConfigured()) {
            $idStatut = $paiement->reference_externe ?? $paiement->reference;
            $result = $maishaPay->checkStatus($idStatut);
            if ($result['success'] && in_array($result['status'], ['SUCCESS', 'success', 'SUCCES', 'PAID', 'paid', 'confirmed', 'CONFIRMED'])) {
                $paiement->update(['statut' => 'paye']);
                $message = 'Paiement confirmé via MaishaPay.';
            } elseif ($result['success'] && in_array($result['status'], ['FAILED', 'failed', 'ECHEC', 'CANCELED', 'canceled'])) {
                $paiement->update(['statut' => 'echoue']);
                return redirect()->route('checkout.confirmation', $commande)
                    ->with('error', 'Paiement echoue.');
            } else {
                $paiement->update(['statut' => 'paye']);
                $message = 'Paiement confirmé.';
            }
        } else {
            $paiement->update(['statut' => 'paye']);
            $message = 'Paiement confirmé. Votre commande est en cours de traitement.';
        }

        return redirect()->route('checkout.confirmation', $commande)
            ->with('success', $message ?? 'Paiement confirmé.');
    }

    public function callback(Request $request)
    {
        $reference = $request->reference;
        $paiement = Paiement::where('reference', $reference)->first();

        if (!$paiement) {
            return response()->json(['error' => 'Transaction introuvable'], 404);
        }

        $paiement->update(['statut' => 'paye']);

        return response()->json(['success' => true]);
    }

    public function status(string $reference)
    {
        $paiement = Paiement::where('reference', $reference)->first();

        if (!$paiement) {
            return response()->json(['status' => 'inconnu']);
        }

        return response()->json([
            'status' => $paiement->statut,
            'reference' => $paiement->reference,
        ]);
    }
}
