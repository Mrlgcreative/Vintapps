<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSetting;
use Illuminate\Http\Request;

class HeroController extends Controller
{
    public function edit()
    {
        $hero = HeroSetting::firstOrCreate(
            ['id' => 1],
            [
                'titre' => 'Bienvenue sur Vintapp',
                'sous_titre' => 'Achetez et vendez au marché Manika de Kolwezi',
                'bouton_texte' => 'Explorer les annonces',
                'bouton_lien' => '/annonces',
                'couleur_fond' => '#333D6D',
                'actif' => true,
            ]
        );

        return view('admin.hero.edit', compact('hero'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'sous_titre' => ['nullable', 'string', 'max:500'],
            'bouton_texte' => ['nullable', 'string', 'max:100'],
            'bouton_lien' => ['nullable', 'string', 'max:255'],
            'couleur_fond' => ['required', 'string', 'max:20'],
            'actif' => ['boolean'],
        ]);

        $validated['actif'] = $request->boolean('actif');

        HeroSetting::updateOrCreate(['id' => 1], $validated);

        return redirect()->route('admin.hero.edit')
            ->with('success', 'Hero mis à jour avec succès.');
    }
}
