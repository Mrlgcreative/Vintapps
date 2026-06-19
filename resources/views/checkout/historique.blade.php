@extends('layouts.public')

@section('title', 'Mes commandes')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Mes commandes</h1>

    @forelse($commandes as $c)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-4">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <a href="{{ route('checkout.confirmation', $c) }}" class="text-sm font-semibold text-gray-900 hover:text-[#723EC3] transition">
                        {{ $c->numero }}
                    </a>
                    <p class="text-xs text-gray-400">{{ $c->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium whitespace-nowrap
                    {{ $c->statut === 'en_attente' ? 'bg-yellow-50 text-yellow-700' : '' }}
                    {{ $c->statut === 'expediee' ? 'bg-blue-50 text-blue-700' : '' }}
                    {{ $c->statut === 'livree' ? 'bg-green-50 text-green-700' : '' }}
                    {{ $c->statut === 'annulee' ? 'bg-red-50 text-red-700' : '' }}">
                    {{ $c->statut === 'en_attente' ? 'En attente' : ($c->statut === 'expediee' ? 'Expédiée' : ($c->statut === 'livree' ? 'Livrée' : ($c->statut === 'annulee' ? 'Annulée' : $c->statut))) }}
                </span>
                @php $p = $c->paiementValide(); @endphp
                @if($p)
                    <span class="text-xs text-green-600 ml-2">Payé</span>
                @endif
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach($c->annonces as $a)
                    <div class="w-10 h-10 rounded-lg bg-gray-100 overflow-hidden">
                        @if($a->url_couverture)
                            <img src="{{ $a->url_couverture }}" alt="" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300 text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="flex justify-end mt-3 pt-3 border-t border-gray-100 items-center gap-3">
                @if($c->statut === 'expediee')
                    <div class="flex-1">
                        <form method="POST" action="{{ route('checkout.recevoir', $c) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition"
                                    onclick="return confirm('Confirmez-vous la réception de cette commande ? Le vendeur sera crédité.')">
                                Commande reçue ✓
                            </button>
                        </form>
                    </div>
                @endif
                @foreach($c->totalParDevise() as $devise => $total)
                    <span class="text-sm font-bold text-[#723EC3]">
                        {{ number_format($total, 0, ',', ' ') }} {{ $devise === 'CDF' ? 'FC' : '$' }}
                    </span>
                @endforeach
            </div>
        </div>
    @empty
        <div class="text-center py-12">
            <p class="text-gray-500 mb-4">Vous n'avez pas encore passé de commande.</p>
            <a href="{{ route('annonces.index') }}" class="inline-flex px-6 py-2.5 bg-[#723EC3] text-white font-medium rounded-lg hover:bg-[#723EC3]/90 transition">
                Découvrir les annonces
            </a>
        </div>
    @endforelse

    @if($commandes->hasPages())
        <div class="mt-6">{{ $commandes->links() }}</div>
    @endif
</div>
@endsection
