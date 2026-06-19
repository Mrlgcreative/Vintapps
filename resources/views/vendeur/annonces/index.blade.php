@extends('layouts.public')

@section('title', 'Mes annonces')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Mes annonces</h1>
            <p class="text-sm text-gray-500 mt-1">Gérez vos annonces en vente.</p>
        </div>
        <a href="{{ route('vendeur.annonces.create') }}"
           class="px-5 py-2.5 bg-[#723EC3] text-white text-sm font-medium rounded-lg hover:bg-[#723EC3]/90 transition">
            + Nouvelle annonce
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid gap-4">
        @forelse($annonces as $a)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
                <div class="w-20 h-20 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0">
                    @if($a->url_couverture)
                        <img src="{{ $a->url_couverture }}" alt="" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-gray-900 truncate">{{ $a->titre }}</h3>
                    <p class="text-sm text-gray-500">{{ $a->categorie?->libelle }} — {{ number_format($a->prix, 0, ',', ' ') }} {{ $a->devise === 'CDF' ? 'FC' : '$' }}</p>
                    <span class="inline-flex mt-1 px-2 py-0.5 rounded-full text-xs font-medium
                        {{ $a->statut === 'publiee' ? 'bg-green-50 text-green-700' : '' }}
                        {{ $a->statut === 'en_attente' ? 'bg-yellow-50 text-yellow-700' : '' }}
                        {{ $a->statut === 'refusee' ? 'bg-red-50 text-red-700' : '' }}">
                        {{ ucfirst(str_replace('_', ' ', $a->statut)) }}
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('vendeur.annonces.edit', $a) }}"
                       class="px-3 py-1.5 text-xs font-medium text-[#723EC3] hover:bg-[#723EC3]/5 rounded-lg transition">Modifier</a>
                    <form method="POST" action="{{ route('vendeur.annonces.destroy', $a) }}" class="inline"
                          onsubmit="return confirm('Supprimer cette annonce ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 rounded-lg transition">Supprimer</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-gray-400">
                <p>Aucune annonce pour le moment.</p>
                <a href="{{ route('vendeur.annonces.create') }}" class="inline-block mt-3 text-sm font-medium text-[#723EC3] hover:text-[#723EC3]/80">
                    Créer votre première annonce
                </a>
            </div>
        @endforelse
    </div>

    @if($annonces->hasPages())
        <div class="mt-6">{{ $annonces->links() }}</div>
    @endif
</div>
@endsection
