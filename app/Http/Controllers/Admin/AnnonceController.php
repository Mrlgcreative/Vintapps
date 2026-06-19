<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Annonce;
use App\Models\Categorie;
use Illuminate\Http\Request;

class AnnonceController extends Controller
{
    public function index(Request $request)
    {
        $query = Annonce::with(['user', 'categorie']);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('titre', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        $annonces = $query->recent()->paginate(15);
        return view('admin.annonces.index', compact('annonces'));
    }

    public function edit(Annonce $annonce)
    {
        $categories = Categorie::all();
        return view('admin.annonces.form', compact('annonce', 'categories'));
    }

    public function update(Request $request, Annonce $annonce)
    {
        $validated = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'prix' => ['required', 'numeric', 'min:0'],
            'devise' => ['nullable', 'in:USD,CDF'],
            'statut' => ['required', 'in:publiee,en_attente,refusee,vendue,archivee'],
            'categorie_id' => ['nullable', 'exists:categories,id'],
        ]);

        $validated['devise'] ??= 'USD';

        $annonce->update($validated);

        return redirect()->route('admin.annonces')
            ->with('success', 'Annonce mise à jour.');
    }

    public function destroy(Annonce $annonce)
    {
        $annonce->delete();
        return redirect()->route('admin.annonces')
            ->with('success', 'Annonce supprimée.');
    }

    public function statut(Request $request, Annonce $annonce)
    {
        $validated = $request->validate([
            'statut' => ['required', 'in:publiee,en_attente,refusee,vendue,archivee'],
        ]);

        $annonce->update(['statut' => $validated['statut']]);

        return back()->with('success', 'Statut mis à jour.');
    }
}
