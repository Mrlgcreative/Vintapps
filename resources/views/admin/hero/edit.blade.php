@extends('layouts.admin')

@section('title', 'Personnaliser le Hero')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Personnaliser le Hero</h1>
        <p class="text-sm text-gray-500 mt-1">Modifiez la bannière principale de la page d'accueil.</p>
    </div>

    <div class="max-w-2xl bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.hero.update') }}">
            @csrf @method('PATCH')

            <div class="space-y-4">
                <div>
                    <label for="titre" class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
                    <input type="text" name="titre" id="titre" value="{{ old('titre', $hero->titre) }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                    <x-input-error :messages="$errors->get('titre')" class="mt-1"/>
                </div>

                <div>
                    <label for="sous_titre" class="block text-sm font-medium text-gray-700 mb-1">Sous-titre</label>
                    <textarea name="sous_titre" id="sous_titre" rows="2"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">{{ old('sous_titre', $hero->sous_titre) }}</textarea>
                    <x-input-error :messages="$errors->get('sous_titre')" class="mt-1"/>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="bouton_texte" class="block text-sm font-medium text-gray-700 mb-1">Texte du bouton</label>
                        <input type="text" name="bouton_texte" id="bouton_texte" value="{{ old('bouton_texte', $hero->bouton_texte) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                    </div>
                    <div>
                        <label for="bouton_lien" class="block text-sm font-medium text-gray-700 mb-1">Lien du bouton</label>
                        <input type="text" name="bouton_lien" id="bouton_lien" value="{{ old('bouton_lien', $hero->bouton_lien) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                    </div>
                </div>

                <div>
                    <label for="couleur_fond" class="block text-sm font-medium text-gray-700 mb-1">Couleur de fond</label>
                    <div class="flex gap-3 items-center">
                        <input type="color" name="couleur_fond" id="couleur_fond" value="{{ old('couleur_fond', $hero->couleur_fond) }}"
                               class="w-10 h-10 rounded cursor-pointer border border-gray-300">
                        <input type="text" value="{{ old('couleur_fond', $hero->couleur_fond) }}"
                               class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3] font-mono">
                    </div>
                    <x-input-error :messages="$errors->get('couleur_fond')" class="mt-1"/>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="actif" id="actif" value="1"
                           {{ old('actif', $hero->actif) ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-gray-300 text-[#723EC3] focus:ring-[#723EC3]">
                    <label for="actif" class="text-sm text-gray-700">Afficher le hero sur la page d'accueil</label>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-3">
                <button type="submit" class="px-6 py-2.5 bg-[#723EC3] text-white text-sm font-medium rounded-lg hover:bg-[#723EC3]/90 transition">
                    Enregistrer
                </button>
                <a href="{{ route('admin.dashboard') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                    Annuler
                </a>
            </div>
        </form>
    </div>
@endsection
