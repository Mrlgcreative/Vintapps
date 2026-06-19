@extends('layouts.public')

@section('title', 'Commande confirmée')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-center">
    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-green-100 flex items-center justify-center">
        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 mb-2">Commande confirmée !</h1>
    <p class="text-gray-500 mb-6">Numéro de commande : <strong class="text-gray-900">{{ $commande->numero }}</strong></p>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6 text-left">
        <div class="flex justify-between">
            <span class="text-sm text-gray-600">Date</span>
            <span class="text-sm font-medium text-gray-900">{{ $commande->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="flex justify-between mt-2">
            <span class="text-sm text-gray-600">Statut</span>
            <span class="text-sm font-medium px-2.5 py-0.5 rounded-full
                {{ $commande->statut === 'en_attente' ? 'bg-yellow-50 text-yellow-700' : '' }}
                {{ $commande->statut === 'expediee' ? 'bg-blue-50 text-blue-700' : '' }}
                {{ $commande->statut === 'livree' ? 'bg-green-50 text-green-700' : '' }}
                {{ $commande->statut === 'annulee' ? 'bg-red-50 text-red-700' : '' }}">
                {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
            </span>
        </div>
        <div class="flex justify-between mt-2">
            <span class="text-sm text-gray-600">Articles</span>
            <span class="text-sm font-medium text-gray-900">{{ $commande->annonces->count() }}</span>
        </div>
        @foreach($commande->totalParDevise() as $devise => $total)
            <div class="flex justify-between mt-2">
                <span class="text-sm text-gray-600">Total {{ $devise === 'CDF' ? 'FC' : 'USD' }}</span>
                <span class="text-sm font-bold text-[#723EC3]">{{ number_format($total, 0, ',', ' ') }} {{ $devise === 'CDF' ? 'FC' : '$' }}</span>
            </div>
        @endforeach
    </div>

    @php $paiement = $commande->paiementValide(); @endphp
    @if($paiement)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6 text-left">
            <h2 class="font-semibold text-gray-900 mb-2">Paiement</h2>
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Méthode</span>
                <span class="font-medium text-gray-900">{{ strtoupper($paiement->operateur) }} (Mobile Money)</span>
            </div>
            <div class="flex justify-between text-sm mt-1">
                <span class="text-gray-600">Téléphone</span>
                <span class="font-medium text-gray-900">{{ $paiement->telephone }}</span>
            </div>
            <div class="flex justify-between text-sm mt-1">
                <span class="text-gray-600">Référence</span>
                <span class="font-medium text-gray-900">{{ $paiement->reference }}</span>
            </div>
            <div class="flex justify-between text-sm mt-1">
                <span class="text-gray-600">Statut</span>
                <span class="font-medium text-green-600">Payé ✓</span>
            </div>
        </div>
    @endif

    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-3 text-left">Articles commandés</h2>
        <div class="space-y-3">
            @foreach($commande->annonces as $a)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex gap-4 items-center">
                    <div class="w-14 h-14 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0">
                        @if($a->url_couverture)
                            <img src="{{ $a->url_couverture }}" alt="" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0 text-left">
                        <p class="text-sm font-semibold text-gray-900">{{ $a->titre }}</p>
                        <p class="text-xs text-gray-400">{{ $a->user?->name }}</p>
                    </div>
                    <div class="text-right text-sm">
                        <p class="text-gray-500">x{{ $a->pivot->quantite }}</p>
                        <p class="font-bold text-[#723EC3]">{{ number_format($a->pivot->prix_unitaire, 0, ',', ' ') }} {{ $a->pivot->devise === 'CDF' ? 'FC' : '$' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="flex justify-center gap-3">
        @if($commande->statut === 'expediee')
            <form method="POST" action="{{ route('checkout.recevoir', $commande) }}">
                @csrf
                <button type="submit" class="px-6 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition"
                        onclick="return confirm('Confirmez-vous la réception de cette commande ? Le vendeur sera crédité.')">
                    Commande reçue ✓
                </button>
            </form>
        @endif
        <a href="{{ route('checkout.historique') }}" class="px-6 py-2.5 bg-[#723EC3] text-white font-medium rounded-lg hover:bg-[#723EC3]/90 transition">
            Mes commandes
        </a>
        <a href="{{ route('annonces.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
            Continuer mes achats
        </a>
    </div>
</div>
@endsection
