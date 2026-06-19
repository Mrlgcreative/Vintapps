@extends('layouts.admin')

@section('title', isset($categorie) ? 'Modifier la catégorie' : 'Nouvelle catégorie')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.categories') }}" class="text-sm text-[#723EC3] hover:text-[#723EC3]/80 transition">&larr; Retour aux catégories</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ isset($categorie) ? 'Modifier' : 'Nouvelle' }} catégorie</h1>
    </div>

    <div class="max-w-lg bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ isset($categorie) ? route('admin.categories.update', $categorie) : route('admin.categories.store') }}">
            @csrf @if(isset($categorie)) @method('PATCH') @endif

            <div class="space-y-4">
                <div>
                    <label for="libelle" class="block text-sm font-medium text-gray-700 mb-1">Libellé</label>
                    <input type="text" name="libelle" id="libelle" value="{{ old('libelle', $categorie->libelle ?? '') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                    <x-input-error :messages="$errors->get('libelle')" class="mt-1"/>
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="2"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">{{ old('description', $categorie->description ?? '') }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="icone" class="block text-sm font-medium text-gray-700 mb-1">Icône (classe CSS)</label>
                        <input type="text" name="icone" id="icone" value="{{ old('icone', $categorie->icone ?? '') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                    </div>
                    <div>
                        <label for="couleur" class="block text-sm font-medium text-gray-700 mb-1">Couleur</label>
                        <div class="flex gap-2 items-center">
                            <input type="color" name="couleur" id="couleur" value="{{ old('couleur', $categorie->couleur ?? '#723EC3') }}"
                                   class="w-10 h-10 rounded cursor-pointer border border-gray-300">
                            <input type="text" value="{{ old('couleur', $categorie->couleur ?? '#723EC3') }}"
                                   class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3] font-mono">
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="ordre" class="block text-sm font-medium text-gray-700 mb-1">Ordre</label>
                        <input type="number" name="ordre" id="ordre" value="{{ old('ordre', $categorie->ordre ?? 0) }}" min="0"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                    </div>
                    <div class="flex items-end pb-2.5">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="actif" value="1" {{ old('actif', $categorie->actif ?? true) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-gray-300 text-[#723EC3] focus:ring-[#723EC3]">
                            <span class="text-sm text-gray-700">Actif</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-3">
                <button type="submit" class="px-6 py-2.5 bg-[#723EC3] text-white text-sm font-medium rounded-lg hover:bg-[#723EC3]/90 transition">
                    {{ isset($categorie) ? 'Enregistrer' : 'Créer' }}
                </button>
                <a href="{{ route('admin.categories') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">Annuler</a>
            </div>
        </form>
    </div>
@endsection
