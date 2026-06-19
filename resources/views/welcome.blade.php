@extends('layouts.public')

@section('title', 'Accueil')

@section('content')
    <x-hero
        :titre="$hero?->titre ?? 'Bienvenue sur Vintapp'"
        :sousTitre="$hero?->sous_titre"
        :boutonTexte="$hero?->bouton_texte"
        :boutonLien="$hero?->bouton_lien ?? route('annonces.index')"
        :couleurFond="$hero?->couleur_fond ?? '#333D6D'"
        :imageFond="$hero?->image_fond"
    />

    <x-categories :categories="$categories" titre="Catégories" />
    <x-annonces :annonces="$annonces" titre="Annonces récentes" :voirPlus="true" />

    @guest
        <x-cta />
    @endguest

    <footer class="bg-white border-t border-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center text-sm text-gray-400">
            &copy; {{ date('Y') }} Vintapp. Tous droits réservés.
        </div>
    </footer>
@endsection
