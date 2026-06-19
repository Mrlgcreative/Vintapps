@extends('layouts.public')

@section('title', 'Mes ventes')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Mes ventes</h1>

    <form method="GET" class="mb-6 flex flex-wrap gap-3">
        <select name="statut" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
            <option value="">Toutes</option>
            <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
            <option value="expediee" {{ request('statut') === 'expediee' ? 'selected' : '' }}>Expédiée</option>
            <option value="livree" {{ request('statut') === 'livree' ? 'selected' : '' }}>Livrée</option>
            <option value="annulee" {{ request('statut') === 'annulee' ? 'selected' : '' }}>Annulée</option>
        </select>
        <button type="submit" class="px-5 py-2.5 bg-[#723EC3] text-white text-sm font-medium rounded-lg hover:bg-[#723EC3]/90 transition">Filtrer</button>
        @if(request()->filled('statut'))
            <a href="{{ route('vendeur.ventes') }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">Réinitialiser</a>
        @endif
    </form>

    <div class="space-y-4">
        @forelse($ventes as $c)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $c->numero }}</p>
                        <p class="text-xs text-gray-400">{{ $c->created_at->format('d/m/Y H:i') }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Acheteur : {{ $c->acheteur?->name }}</p>
                    </div>
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $c->statut === 'en_attente' ? 'bg-yellow-50 text-yellow-700' : '' }}
                        {{ $c->statut === 'expediee' ? 'bg-blue-50 text-blue-700' : '' }}
                        {{ $c->statut === 'livree' ? 'bg-green-50 text-green-700' : '' }}
                        {{ $c->statut === 'annulee' ? 'bg-red-50 text-red-700' : '' }}">
                        {{ ucfirst(str_replace('_', ' ', $c->statut)) }}
                    </span>
                </div>

                <div class="space-y-2">
                    @foreach($c->annonces as $a)
                        <div class="flex items-center gap-3 text-sm">
                            <div class="w-10 h-10 rounded bg-gray-100 overflow-hidden flex-shrink-0">
                                @if($a->url_couverture)
                                    <img src="{{ $a->url_couverture }}" alt="" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 truncate">{{ $a->titre }}</p>
                            </div>
                            <p class="font-semibold text-[#723EC3] whitespace-nowrap">
                                {{ number_format($a->pivot->prix_unitaire * $a->pivot->quantite, 0, ',', ' ') }}
                                {{ $a->pivot->devise === 'CDF' ? 'FC' : '$' }}
                            </p>
                        </div>
                    @endforeach
                </div>

                @if($c->statut === 'en_attente')
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <form method="POST" action="{{ route('vendeur.ventes.expedier', $c) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-[#333D6D] text-white text-sm font-medium rounded-lg hover:bg-[#333D6D]/90 transition">
                                Marquer comme expédiée
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-12 text-gray-500">Aucune vente trouvée.</div>
        @endforelse
    </div>

    @if($ventes->hasPages())
        <div class="mt-6">{{ $ventes->links() }}</div>
    @endif
</div>
@endsection
