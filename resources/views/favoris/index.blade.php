@extends('layouts.public')

@section('title', 'Mes favoris')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Mes favoris</h1>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg">{{ session('success') }}</div>
    @endif

    @if($annonces->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            <p class="text-gray-500 mb-4">Vous n'avez aucun favori.</p>
            <a href="{{ route('annonces.index') }}" class="inline-flex px-5 py-2.5 bg-[#723EC3] text-white text-sm font-medium rounded-lg hover:bg-[#723EC3]/90 transition">
                Explorer les annonces
            </a>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($annonces as $annonce)
                <a href="{{ route('annonces.show', $annonce) }}"
                   class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition relative">
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
                    <form method="POST" action="{{ route('favoris.toggle', $annonce) }}" class="absolute top-2 right-2">
                        @csrf
                        <button type="submit" class="p-1.5 bg-white/80 rounded-full hover:bg-white transition">
                            <svg class="w-5 h-5 text-red-500" fill="currentColor" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </button>
                    </form>
                </a>
            @endforeach
        </div>

        @if($annonces->hasPages())
            <div class="mt-6">{{ $annonces->links() }}</div>
        @endif
    @endif
</div>
@endsection
