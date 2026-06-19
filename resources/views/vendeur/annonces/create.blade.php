@extends('layouts.public')

@section('title', 'Nouvelle annonce')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="{{ route('vendeur.annonces.index') }}" class="text-sm text-[#723EC3] hover:text-[#723EC3]/80 transition">&larr; Mes annonces</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Nouvelle annonce</h1>
    </div>

    <form action="{{ route('vendeur.annonces.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
            <div>
                <label for="titre" class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
                <input type="text" name="titre" id="titre" value="{{ old('titre') }}" required
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]"
                       placeholder="Ex: Samsung Galaxy S24 256 Go">
                <x-input-error :messages="$errors->get('titre')" class="mt-1"/>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="5"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]"
                          placeholder="Décrivez votre article en détail...">{{ old('description') }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-1"/>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label for="prix" class="block text-sm font-medium text-gray-700 mb-1">Prix</label>
                    <div class="flex gap-2">
                        <input type="number" name="prix" id="prix" value="{{ old('prix') }}" required step="0.01" min="0"
                               class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]"
                               placeholder="0.00">
                        <select name="devise" id="devise" required
                                class="w-24 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                            <option value="USD" {{ old('devise') === 'USD' ? 'selected' : '' }}>$</option>
                            <option value="CDF" {{ old('devise') === 'CDF' ? 'selected' : '' }}>FC</option>
                        </select>
                    </div>
                    <x-input-error :messages="$errors->get('prix')" class="mt-1"/>
                    <x-input-error :messages="$errors->get('devise')" class="mt-1"/>
                </div>
                <div>
                    <label for="categorie_id" class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                    <select name="categorie_id" id="categorie_id" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                        <option value="">Sélectionnez</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('categorie_id') == $cat->id ? 'selected' : '' }}>{{ $cat->libelle }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('categorie_id')" class="mt-1"/>
                </div>
            </div>

            <div>
                <span class="block text-sm font-medium text-gray-700 mb-2">État</span>
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-2">
                    @foreach(['neuf' => 'Neuf', 'tres_bon_etat' => 'Très bon état', 'bon_etat' => 'Bon état', 'usage' => 'Usage', 'endommage' => 'Endommagé'] as $val => $label)
                        <label class="flex items-center gap-2 px-3 py-2.5 border border-gray-300 rounded-lg cursor-pointer has-[:checked]:border-[#723EC3] has-[:checked]:bg-[#723EC3]/5 transition">
                            <input type="radio" name="etat" value="{{ $val }}" {{ old('etat') === $val ? 'checked' : '' }} required
                                   class="w-4 h-4 text-[#723EC3] focus:ring-[#723EC3] border-gray-300">
                            <span class="text-sm text-gray-700">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('etat')" class="mt-1"/>
            </div>
        </div>

        {{-- Photos --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <span class="block text-sm font-medium text-gray-700 mb-1">Photos (max 5)</span>
            <p class="text-xs text-gray-400 mb-4">Formats : JPG, PNG, WEBP. Max 5 Mo par image.</p>

            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-[#723EC3]/70 transition cursor-pointer"
                 onclick="document.getElementById('photos').click()">
                <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm text-gray-500">Cliquez ou glissez vos photos ici</p>
            </div>
            <input type="file" name="photos[]" id="photos" multiple accept="image/*" class="hidden">

            <div id="preview" class="grid grid-cols-5 gap-2 mt-4"></div>
            <x-input-error :messages="$errors->get('photos')" class="mt-1"/>
            <x-input-error :messages="$errors->get('photos.*')" class="mt-1"/>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-8 py-3 bg-[#723EC3] text-white font-semibold rounded-lg hover:bg-[#723EC3]/90 transition">
                Publier l'annonce
            </button>
            <a href="{{ route('vendeur.annonces.index') }}"
               class="px-8 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                Annuler
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('photos').addEventListener('change', function(e) {
    const preview = document.getElementById('preview');
    preview.innerHTML = '';
    Array.from(e.target.files).slice(0, 5).forEach(file => {
        const reader = new FileReader();
        reader.onload = function(ev) {
            const div = document.createElement('div');
            div.className = 'aspect-square rounded-lg overflow-hidden bg-gray-100 border border-gray-200';
            div.innerHTML = `<img src="${ev.target.result}" class="w-full h-full object-cover">`;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush
@endsection
