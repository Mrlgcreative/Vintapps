<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

use App\Models\Annonce;
use App\Models\Categorie;
use Illuminate\Http\Request;

class AnnonceController extends Controller
{
    public function index(Request $request)
    {
        $query = Annonce::publiee()->with('categorie');

        if ($request->filled('keyword')) {
            $k = $request->keyword;
            $query->where(function ($q) use ($k) {
                $q->where('titre', 'like', "%{$k}%")
                  ->orWhere('description', 'like', "%{$k}%");
            });
        }

        if ($request->filled('categorie')) {
            $query->whereHas('categorie', fn($q) => $q->where('slug', $request->categorie));
        }

        if ($request->filled('prix_min')) {
            $query->where('prix', '>=', $request->prix_min);
        }

        if ($request->filled('prix_max')) {
            $query->where('prix', '<=', $request->prix_max);
        }

        if ($request->filled('etat')) {
            $query->where('etat', $request->etat);
        }

        if ($request->filled('vendeur')) {
            $query->where('user_id', $request->vendeur);
        }

        $annonces = $query->recent()->paginate(12);
        $categories = Categorie::where('actif', true)->orderBy('ordre')->get();

        return view('annonces.index', compact('annonces', 'categories'));
    }

    public function show(Annonce $annonce)
    {
        abort_unless($annonce->statut === 'publiee', 404);

        $annonce->increment('vues');
        $annonce->load(['categorie', 'photos', 'user']);

        $similaires = Annonce::publiee()
            ->where('categorie_id', $annonce->categorie_id)
            ->where('id', '!=', $annonce->id)
            ->with(['photos'])
            ->recent()
            ->take(4)
            ->get();

        return view('annonces.show', compact('annonce', 'similaires'));
    }
}
