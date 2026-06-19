<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use Illuminate\Http\Request;

class FavoriController extends Controller
{
    public function index()
    {
        $annonces = auth()->user()->favoris()
            ->with(['photos', 'categorie'])
            ->recent()
            ->paginate(12);

        return view('favoris.index', compact('annonces'));
    }

    public function toggle(Annonce $annonce)
    {
        $user = auth()->user();

        if ($user->estDansFavoris($annonce)) {
            $user->favoris()->detach($annonce);
            $retire = true;
        } else {
            $user->favoris()->attach($annonce);
            $retire = false;
        }

        if (request()->wantsJson()) {
            return response()->json(['favori' => !$retire]);
        }

        return back()->with('success', $retire ? 'Annonce retirée des favoris.' : 'Annonce ajoutée aux favoris.');
    }
}
