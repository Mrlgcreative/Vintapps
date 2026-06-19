@extends('layouts.admin')

@section('title', 'Modifier l\'annonce')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.annonces') }}" class="text-sm text-[#723EC3] hover:text-[#723EC3]/80 transition">&larr; Retour aux annonces</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Modifier l'annonce</h1>
    </div>

    <div class="max-w-lg bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.annonces.update', $annonce) }}">
            @csrf @method('PATCH')

            <div class="space-y-4">
                <div>
                    <label for="titre" class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
                    <input type="text" name="titre" id="titre" value="{{ old('titre', $annonce->titre) }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">{{ old('description', $annonce->description) }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="prix" class="block text-sm font-medium text-gray-700 mb-1">Prix</label>
                        <div class="flex gap-2">
                            <input type="number" name="prix" id="prix" value="{{ old('prix', $annonce->prix) }}" required step="0.01" min="0"
                                   class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                            <select name="devise" id="devise"
                                    class="w-24 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                                <option value="USD" {{ old('devise', $annonce->devise ?? 'USD') === 'USD' ? 'selected' : '' }}>$</option>
                                <option value="CDF" {{ old('devise', $annonce->devise ?? 'USD') === 'CDF' ? 'selected' : '' }}>FC</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="categorie_id" class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                        <select name="categorie_id" id="categorie_id"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                            <option value="">Sélectionner</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('categorie_id', $annonce->categorie_id) == $cat->id ? 'selected' : '' }}>{{ $cat->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="statut" id="statut" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                        <option value="publiee" {{ old('statut', $annonce->statut) === 'publiee' ? 'selected' : '' }}>Publiée</option>
                        <option value="en_attente" {{ old('statut', $annonce->statut) === 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="refusee" {{ old('statut', $annonce->statut) === 'refusee' ? 'selected' : '' }}>Refusée</option>
                        <option value="vendue" {{ old('statut', $annonce->statut) === 'vendue' ? 'selected' : '' }}>Vendue</option>
                        <option value="archivee" {{ old('statut', $annonce->statut) === 'archivee' ? 'selected' : '' }}>Archivée</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-3">
                <button type="submit" class="px-6 py-2.5 bg-[#723EC3] text-white text-sm font-medium rounded-lg hover:bg-[#723EC3]/90 transition">Enregistrer</button>
                <a href="{{ route('admin.annonces') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">Annuler</a>
            </div>
        </form>
    </div>
@endsection
