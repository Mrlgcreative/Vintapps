<?php

namespace App\Http\Controllers\Vendeur;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\Conversation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_annonces' => Annonce::where('user_id', $user->id)->count(),
            'annonces_publiees' => Annonce::where('user_id', $user->id)->where('statut', 'publiee')->count(),
            'vues_total' => Annonce::where('user_id', $user->id)->sum('vues'),
            'messages_non_lus' => Conversation::pourUtilisateur($user->id)->get()->sum(
                fn($c) => $c->messages()->where('user_id', '!=', $user->id)->where('lu', false)->count()
            ),
            'note_moyenne' => $user->noteMoyenne(),
            'nb_avis' => $user->evaluationsRecues()->count(),
        ];

        $recentes = Annonce::where('user_id', $user->id)
            ->with(['categorie'])
            ->recent()
            ->take(5)
            ->get();

        $conversations = Conversation::pourUtilisateur($user->id)
            ->with(['annonce', 'acheteur', 'vendeur', 'dernierMessage'])
            ->orderByDesc('dernier_message_at')
            ->take(5)
            ->get();

        return view('vendeur.dashboard', compact('stats', 'recentes', 'conversations'));
    }
}
