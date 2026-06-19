<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use App\Models\Evaluation;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function store(Request $request, Annonce $annonce)
    {
        abort_unless($annonce->user_id !== auth()->id(), 403, 'Vous ne pouvez pas vous évaluer vous-même.');

        $validated = $request->validate([
            'note' => ['required', 'integer', 'min:1', 'max:5'],
            'commentaire' => ['nullable', 'string', 'max:2000'],
        ]);

        Evaluation::updateOrCreate(
            ['acheteur_id' => auth()->id(), 'annonce_id' => $annonce->id],
            [
                'vendeur_id' => $annonce->user_id,
                'note' => $validated['note'],
                'commentaire' => $validated['commentaire'],
            ]
        );

        return back()->with('success', 'Évaluation enregistrée.');
    }

    public function vendeur(\App\Models\User $user)
    {
        $evaluations = $user->evaluationsRecues()
            ->with(['acheteur', 'annonce'])
            ->latest()
            ->paginate(10);

        $moyenne = $user->noteMoyenne();
        $total = $user->evaluationsRecues()->count();

        return view('evaluations.vendeur', compact('user', 'evaluations', 'moyenne', 'total'));
    }
}
