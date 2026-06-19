@props([
    'titre' => 'Bienvenue sur Vintapp',
    'sousTitre' => 'Achetez et vendez au marché Manika de Kolwezi',
    'boutonTexte' => 'Explorer les annonces',
    'boutonLien' => '#',
    'couleurFond' => '#333D6D',
    'imageFond' => null,
])

<section class="relative overflow-hidden" style="background-color: {{ $couleurFond }};">
    @if($imageFond)
        <div class="absolute inset-0">
            <img src="{{ Storage::url($imageFond) }}" alt="" class="w-full h-full object-cover opacity-20">
            <div class="absolute inset-0" style="background: linear-gradient(to right, {{ $couleurFond }} 0%, transparent 100%);"></div>
        </div>
    @endif
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28 lg:py-36">
        <div class="max-w-2xl">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight">
                {{ $titre }}
            </h1>
            @if($sousTitre)
                <p class="mt-4 text-lg sm:text-xl text-white/80 max-w-xl">
                    {{ $sousTitre }}
                </p>
            @endif
            @if($boutonTexte)
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ $boutonLien }}"
                       class="inline-flex items-center px-6 py-3 bg-[#FFCF95] text-[#333D6D] font-semibold rounded-lg hover:bg-[#FFCF95]/90 transition text-sm sm:text-base">
                        {{ $boutonTexte }}
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
