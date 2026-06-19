@extends('layouts.public')

@section('title', 'Récapitulatif de la commande')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Récapitulatif de la commande</h1>

    <div class="space-y-4 mb-6">
        @foreach($annonces as $a)
            @php $qte = $cart[$a->id]['quantite'] ?? 1; @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex gap-4 items-center">
                <div class="w-16 h-16 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0">
                    @if($a->url_couverture)
                        <img src="{{ $a->url_couverture }}" alt="" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900">{{ $a->titre }}</p>
                    <p class="text-xs text-gray-400">{{ $a->user?->boutique_name ?: $a->user?->name }}</p>
                    <p class="text-xs text-gray-500 mt-1">Qté : {{ $qte }}</p>
                </div>
                <p class="text-sm font-bold text-[#723EC3] whitespace-nowrap">
                    {{ number_format($a->prix * $qte, 0, ',', ' ') }} {{ $a->devise === 'CDF' ? 'FC' : '$' }}
                </p>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h2 class="font-semibold text-gray-900 mb-2">Totaux</h2>
        <div class="space-y-1">
            @foreach($totaux as $devise => $total)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Total {{ $devise === 'CDF' ? 'FC' : 'USD' }}</span>
                    <span class="font-bold text-[#723EC3]">{{ number_format($total, 0, ',', ' ') }} {{ $devise === 'CDF' ? 'FC' : '$' }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h2 class="font-semibold text-gray-900 mb-1">Paiement Mobile Money</h2>
        <p class="text-xs text-gray-500 mb-4">Choisissez votre opérateur et entrez votre numéro pour payer.</p>
        <form method="POST" action="{{ route('checkout.payer') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Opérateur</label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="flex flex-col items-center gap-1 p-3 border rounded-lg cursor-pointer has-[:checked]:border-[#723EC3] has-[:checked]:bg-[#723EC3]/5 transition">
                        <input type="radio" name="operateur" value="mpesa" class="sr-only" checked>
                        <span class="text-lg font-bold text-green-600">M-Pesa</span>
                        <span class="text-xs text-gray-400">Vodacom</span>
                    </label>
                    <label class="flex flex-col items-center gap-1 p-3 border rounded-lg cursor-pointer has-[:checked]:border-[#723EC3] has-[:checked]:bg-[#723EC3]/5 transition">
                        <input type="radio" name="operateur" value="airtel" class="sr-only">
                        <span class="text-lg font-bold text-red-600">Airtel</span>
                        <span class="text-xs text-gray-400">Money</span>
                    </label>
                    <label class="flex flex-col items-center gap-1 p-3 border rounded-lg cursor-pointer has-[:checked]:border-[#723EC3] has-[:checked]:bg-[#723EC3]/5 transition">
                        <input type="radio" name="operateur" value="orange" class="sr-only">
                        <span class="text-lg font-bold text-orange-600">Orange</span>
                        <span class="text-xs text-gray-400">Money</span>
                    </label>
                </div>
                @error('operateur') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">Numéro de téléphone</label>
                <input type="tel" name="telephone" id="telephone" value="{{ old('telephone', auth()->user()->phone) }}" required
                       placeholder="+243 XX XXX XXXX"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                @error('telephone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes pour le vendeur (optionnel)</label>
                <textarea name="notes" id="notes" rows="2" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">{{ old('notes') }}</textarea>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-8 py-3 bg-[#333D6D] text-white font-semibold rounded-lg hover:bg-[#333D6D]/90 transition">
                    Payer via Mobile Money
                </button>
                <a href="{{ route('cart.index') }}" class="text-sm text-gray-500 hover:text-gray-700 transition">Modifier le panier</a>
            </div>
        </form>
    </div>
</div>
@endsection
