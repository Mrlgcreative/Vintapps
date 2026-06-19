<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class MessagingController extends Controller
{
    public function index()
    {
        $conversations = Conversation::pourUtilisateur(auth()->id())
            ->with(['annonce', 'acheteur', 'vendeur', 'dernierMessage'])
            ->orderByDesc('dernier_message_at')
            ->get();

        $nonLu = $conversations->sum(fn($c) => $c->messages()
            ->where('user_id', '!=', auth()->id())
            ->where('lu', false)
            ->count()
        );

        return view('messages.index', compact('conversations', 'nonLu'));
    }

    public function show(Conversation $conversation)
    {
        abort_unless(
            $conversation->acheteur_id === auth()->id() || $conversation->vendeur_id === auth()->id(),
            403
        );

        $conversation->load(['annonce', 'acheteur', 'vendeur', 'messages.user']);
        $conversation->marquerLu(auth()->id());

        return view('messages.show', compact('conversation'));
    }

    public function envoyer(Request $request, Conversation $conversation)
    {
        abort_unless(
            $conversation->acheteur_id === auth()->id() || $conversation->vendeur_id === auth()->id(),
            403
        );

        $validated = $request->validate([
            'contenu' => ['required', 'string', 'max:5000'],
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => auth()->id(),
            'contenu' => $validated['contenu'],
        ]);

        $conversation->update(['dernier_message_at' => now()]);

        return back();
    }

    public function contacter(Request $request, Annonce $annonce)
    {
        abort_unless($annonce->statut === 'publiee', 404);
        abort_if($annonce->user_id === auth()->id(), 403, 'Vous ne pouvez pas vous envoyer un message à vous-même.');

        $conversation = Conversation::firstOrCreate(
            ['annonce_id' => $annonce->id, 'acheteur_id' => auth()->id()],
            ['vendeur_id' => $annonce->user_id],
        );

        if ($request->filled('message')) {
            Message::create([
                'conversation_id' => $conversation->id,
                'user_id' => auth()->id(),
                'contenu' => $request->message,
            ]);
            $conversation->update(['dernier_message_at' => now()]);
        }

        return redirect()->route('messages.show', $conversation);
    }
}
