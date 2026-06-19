@extends('layouts.public')

@section('title', 'Annonces')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <form method="GET" action="{{ route('annonces.index') }}" class="sticky top-0 z-20 bg-gray-50 py-4 border-b border-gray-200 mb-6 space-y-3">
            <div class="flex flex-wrap gap-3">
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Rechercher..."
                       class="flex-1 min-w-[200px] px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                <select name="categorie"
                        class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ request('categorie') === $cat->slug ? 'selected' : '' }}>{{ $cat->libelle }}</option>
                    @endforeach
                </select>
                <select name="etat"
                        class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                    <option value="">Tous les états</option>
                    <option value="neuf" {{ request('etat') === 'neuf' ? 'selected' : '' }}>Neuf</option>
                    <option value="tres_bon_etat" {{ request('etat') === 'tres_bon_etat' ? 'selected' : '' }}>Très bon état</option>
                    <option value="bon_etat" {{ request('etat') === 'bon_etat' ? 'selected' : '' }}>Bon état</option>
                    <option value="usage" {{ request('etat') === 'usage' ? 'selected' : '' }}>Usage</option>
                    <option value="endommage" {{ request('etat') === 'endommage' ? 'selected' : '' }}>Endommagé</option>
                </select>
                <button type="submit" class="px-5 py-2.5 bg-[#723EC3] text-white text-sm font-medium rounded-lg hover:bg-[#723EC3]/90 transition">Filtrer</button>
            </div>
            <div class="flex flex-wrap gap-3 items-center">
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500">Prix :</span>
                    <input type="number" name="prix_min" value="{{ request('prix_min') }}" placeholder="Min"
                           class="w-24 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                    <span class="text-gray-400">&mdash;</span>
                    <input type="number" name="prix_max" value="{{ request('prix_max') }}" placeholder="Max"
                           class="w-24 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                </div>
                @if(request()->anyFilled(['keyword', 'categorie', 'etat', 'prix_min', 'prix_max']))
                    <a href="{{ route('annonces.index') }}" class="text-xs text-gray-500 hover:text-[#723EC3] transition">Réinitialiser</a>
                @endif
            </div>
        </form>

        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            @forelse($annonces as $annonce)
                <a href="{{ route('annonces.show', $annonce) }}"
                   class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                    <div class="aspect-[4/3] bg-gray-100 overflow-hidden">
                        @if($annonce->url_couverture)
                            <img src="{{ $annonce->url_couverture }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-3 sm:p-4">
                        <h3 class="font-semibold text-gray-900 text-sm sm:text-base line-clamp-1 group-hover:text-[#723EC3] transition">{{ $annonce->titre }}</h3>
                        <p class="text-lg font-bold text-[#723EC3] mt-1">{{ number_format($annonce->prix, 0, ',', ' ') }} {{ $annonce->devise === 'CDF' ? 'FC' : '$' }}</p>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-12 text-gray-400">Aucune annonce trouvée.</div>
            @endforelse
        </div>

        @if($annonces->hasPages())
            <div class="mt-6">{{ $annonces->links() }}</div>
        @endif
    </div>
@endsection
