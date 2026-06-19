<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use Illuminate\Http\Request;

class SignalementController extends Controller
{
    public function store(Request $request, Annonce $annonce)
    {
        $validated = $request->validate([
            'motif' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $annonce->signalements()->create([
            'signalé_par' => auth()->id(),
            'motif' => $validated['motif'],
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('success', 'Annonce signalée. Merci de votre vigilance.');
    }
}
