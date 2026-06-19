@extends('layouts.public')

@section('title', 'Panier')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Mon panier</h1>
        @if($count > 0)
            <span class="text-sm text-gray-500">{{ $count }} article(s)</span>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg">{{ session('success') }}</div>
    @endif

    @if($annonces->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
            </svg>
            <p class="text-gray-500 mb-4">Votre panier est vide.</p>
            <a href="{{ route('annonces.index') }}" class="inline-flex px-5 py-2.5 bg-[#723EC3] text-white text-sm font-medium rounded-lg hover:bg-[#723EC3]/90 transition">
                Explorer les annonces
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($annonces as $annonce)
                @php $qte = $cart[$annonce->id]['quantite'] ?? 1; @endphp
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex gap-4 items-center">
                    <div class="w-20 h-20 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0">
                        @if($annonce->url_couverture)
                            <img src="{{ $annonce->url_couverture }}" alt="" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('annonces.show', $annonce) }}" class="text-sm font-semibold text-gray-900 hover:text-[#723EC3] transition line-clamp-1">{{ $annonce->titre }}</a>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $annonce->user?->boutique_name ?: $annonce->user?->name }}</p>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-sm font-bold text-[#723EC3]">
                                {{ number_format($annonce->prix * $qte, 0, ',', ' ') }}
                                <span class="text-xs font-normal text-gray-400">{{ $annonce->devise === 'CDF' ? 'FC' : 'USD' }}</span>
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <form method="POST" action="{{ route('cart.update', $annonce) }}" class="flex items-center border border-gray-200 rounded-lg">
                            @csrf
                            <button type="submit" name="qte" value="{{ $qte - 1 }}" class="px-2 py-1.5 text-gray-500 hover:text-[#723EC3] transition text-sm font-medium" {{ $qte <= 1 ? 'disabled' : '' }}>&minus;</button>
                            <span class="px-2 py-1.5 text-sm font-medium text-gray-900 min-w-[24px] text-center">{{ $qte }}</span>
                            <button type="submit" name="qte" value="{{ $qte + 1 }}" class="px-2 py-1.5 text-gray-500 hover:text-[#723EC3] transition text-sm font-medium">+</button>
                        </form>
                        <form method="POST" action="{{ route('cart.remove', $annonce) }}">
                            @csrf
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition" title="Retirer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-3">
            @foreach($totaux as $devise => $total)
                <div class="flex items-center justify-between">
                    <span class="text-gray-700 font-medium">Total {{ $devise === 'CDF' ? 'FC' : 'USD' }}</span>
                    <span class="text-lg font-bold text-[#723EC3]">{{ number_format($total, 0, ',', ' ') }} {{ $devise === 'CDF' ? 'FC' : '$' }}</span>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
            <form method="POST" action="{{ route('cart.clear') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-500 hover:text-red-500 transition">Vider le panier</button>
            </form>
            <div class="flex gap-3">
                <a href="{{ route('annonces.index') }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                    Continuer mes achats
                </a>
                @auth
                    <a href="{{ route('checkout.recap') }}" class="px-6 py-2.5 bg-[#333D6D] text-white text-sm font-medium rounded-lg hover:bg-[#333D6D]/90 transition text-center">
                        Commander
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-2.5 bg-[#333D6D] text-white text-sm font-medium rounded-lg hover:bg-[#333D6D]/90 transition text-center">
                        Connectez-vous pour commander
                    </a>
                @endauth
            </div>
        </div>
    @endif
</div>
@endsection
