<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategorieController extends Controller
{
    public function index()
    {
        $categories = Categorie::orderBy('ordre')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'icone' => ['nullable', 'string', 'max:100'],
            'couleur' => ['required', 'string', 'max:20'],
            'ordre' => ['nullable', 'integer', 'min:0'],
            'actif' => ['boolean'],
        ]);

        $validated['slug'] = Str::slug($validated['libelle']);
        $validated['actif'] = $request->boolean('actif');

        Categorie::create($validated);

        return redirect()->route('admin.categories')
            ->with('success', 'Catégorie créée avec succès.');
    }

    public function edit(Categorie $categorie)
    {
        return view('admin.categories.form', compact('categorie'));
    }

    public function update(Request $request, Categorie $categorie)
    {
        $validated = $request->validate([
            'libelle' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'icone' => ['nullable', 'string', 'max:100'],
            'couleur' => ['required', 'string', 'max:20'],
            'ordre' => ['nullable', 'integer', 'min:0'],
            'actif' => ['boolean'],
        ]);

        $validated['slug'] = Str::slug($validated['libelle']);
        $validated['actif'] = $request->boolean('actif');

        $categorie->update($validated);

        return redirect()->route('admin.categories')
            ->with('success', 'Catégorie mise à jour avec succès.');
    }

    public function destroy(Categorie $categorie)
    {
        $categorie->delete();
        return redirect()->route('admin.categories')
            ->with('success', 'Catégorie supprimée.');
    }
}
