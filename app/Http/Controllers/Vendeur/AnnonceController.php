<?php

namespace App\Http\Controllers\Vendeur;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnnonceRequest;
use App\Models\Annonce;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnonceController extends Controller
{
    private function verifierVendeur(): void
    {
        if (!auth()->user()?->estVendeur()) {
            abort(403, 'Seuls les vendeurs peuvent gérer les annonces.');
        }
    }

    public function index()
    {
        $this->verifierVendeur();

        $annonces = Annonce::where('user_id', auth()->id())
            ->with(['categorie', 'photos'])
            ->recent()
            ->paginate(12);

        return view('vendeur.annonces.index', compact('annonces'));
    }

    public function create()
    {
        $this->verifierVendeur();

        $categories = Categorie::where('actif', true)->orderBy('ordre')->get();
        return view('vendeur.annonces.create', compact('categories'));
    }

    public function store(StoreAnnonceRequest $request)
    {
        $this->verifierVendeur();

        $annonce = auth()->user()->annonces()->create($request->validated());

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $path = $this->traiterPhoto($photo);
                $annonce->photos()->create([
                    'chemin_fichier' => $path,
                    'est_couverture' => $index === 0,
                    'texte_alt' => $annonce->titre,
                ]);
            }
        }

        return redirect()->route('vendeur.annonces.index')
            ->with('success', 'Annonce créée avec succès.');
    }

    public function edit(Annonce $annonce)
    {
        if ($annonce->user_id !== auth()->id()) {
            abort(403);
        }

        $categories = Categorie::where('actif', true)->orderBy('ordre')->get();
        $annonce->load('photos');

        return view('vendeur.annonces.edit', compact('annonce', 'categories'));
    }

    public function update(StoreAnnonceRequest $request, Annonce $annonce)
    {
        if ($annonce->user_id !== auth()->id()) {
            abort(403);
        }

        $annonce->update($request->validated());

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $path = $this->traiterPhoto($photo);
                $annonce->photos()->create([
                    'chemin_fichier' => $path,
                    'est_couverture' => $index === 0 && $annonce->photos()->count() === 0,
                    'texte_alt' => $annonce->titre,
                ]);
            }
        }

        return redirect()->route('vendeur.annonces.index')
            ->with('success', 'Annonce mise à jour.');
    }

    public function destroy(Annonce $annonce)
    {
        if ($annonce->user_id !== auth()->id()) {
            abort(403);
        }

        foreach ($annonce->photos as $photo) {
            Storage::disk('public')->delete($photo->chemin_fichier);
        }
        $annonce->delete();

        return redirect()->route('vendeur.annonces.index')
            ->with('success', 'Annonce supprimée.');
    }

    public function supprimerPhoto($photoId)
    {
        $photo = \App\Models\Photo::findOrFail($photoId);
        $annonce = $photo->annonce;

        if ($annonce->user_id !== auth()->id()) {
            abort(403);
        }

        Storage::disk('public')->delete($photo->chemin_fichier);
        $photo->delete();

        return back()->with('success', 'Photo supprimée.');
    }

    private function traiterPhoto($photo)
    {
        $manager = new \Intervention\Image\ImageManager(
            new \Intervention\Image\Drivers\Gd\Driver
        );

        $image = $manager->decodeSplFileInfo($photo);
        $image->scaleDown(width: 1200);

        $filename = uniqid('annonce_') . '.webp';
        $path = 'annonces/' . $filename;

        $fullPath = storage_path('app/public/' . $path);
        if (!is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        $image->save($fullPath, quality: 80);

        return $path;
    }
}
