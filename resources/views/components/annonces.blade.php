@props([
    'annonces' => [],
    'titre' => 'Annonces récentes',
    'voirPlus' => true,
])

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <div class="flex items-end justify-between mb-8">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $titre }}</h2>
            <p class="mt-1 text-gray-500">Les dernières annonces publiées</p>
        </div>
        @if($voirPlus)
            <a href="{{ route('annonces.index') }}"
               class="hidden sm:inline-flex items-center text-sm font-medium text-[#723EC3] hover:text-[#723EC3]/80 transition">
                Voir tout
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        @endif
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        @forelse($annonces as $annonce)
            <a href="{{ route('annonces.show', $annonce) }}"
               class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-200">
                <div class="aspect-[4/3] bg-gray-100 overflow-hidden">
                    @if($annonce->url_couverture)
                        <img src="{{ $annonce->url_couverture }}" alt="{{ $annonce->titre }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300" loading="lazy">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="p-3 sm:p-4">
                    <h3 class="font-semibold text-gray-900 text-sm sm:text-base line-clamp-1 group-hover:text-[#723EC3] transition">
                        {{ $annonce->titre }}
                    </h3>
                    <p class="text-lg font-bold text-[#723EC3] mt-1">
                        {{ number_format($annonce->prix, 0, ',', ' ') }} {{ $annonce->devise === 'CDF' ? 'FC' : '$' }}
                    </p>
                    @if($annonce->categorie)
                        <span class="text-xs text-gray-400 mt-1 block">{{ $annonce->categorie->libelle }}</span>
                    @endif
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-12 text-gray-400">
                Aucune annonce pour le moment.
            </div>
        @endforelse
    </div>

    @if($voirPlus)
        <div class="text-center mt-8 sm:hidden">
            <a href="{{ route('annonces.index') }}"
               class="inline-flex items-center px-5 py-2.5 border border-[#723EC3] text-[#723EC3] font-medium rounded-lg hover:bg-[#723EC3]/5 transition text-sm">
                Voir toutes les annonces
                <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    @endif
</section>
