@props([
    'titre' => 'Prêt à vendre ?',
    'sousTitre' => 'Inscrivez-vous gratuitement et commencez à vendre vos articles dès aujourd\'hui sur Vintapp.',
    'boutonTexte' => 'Créer un compte',
    'boutonLien' => route('register'),
    'couleurFond' => '#FFF0D9',
])

<section class="py-12 sm:py-16" style="background-color: {{ $couleurFond }};">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $titre }}</h2>
        <p class="mt-3 text-gray-600">{{ $sousTitre }}</p>
        @if($boutonTexte)
            <div class="mt-8">
                <a href="{{ $boutonLien }}"
                   class="inline-flex items-center px-6 py-3 bg-[#723EC3] text-white font-semibold rounded-lg hover:bg-[#723EC3]/90 transition text-sm sm:text-base">
                    {{ $boutonTexte }}
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>
        @endif
    </div>
</section>
