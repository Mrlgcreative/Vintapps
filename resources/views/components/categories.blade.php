@props([
    'categories' => [],
    'titre' => 'Catégories',
])

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <div class="text-center mb-10">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $titre }}</h2>
        <p class="mt-2 text-gray-500">Explorez nos catégories d'articles</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        @forelse($categories as $cat)
            <a href="{{ route('annonces.index', ['categorie' => $cat->slug]) }}"
               class="group bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center hover:shadow-md hover:border-{{ $cat->couleur }} transition-all duration-200">
                @if($cat->icone)
                    <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center text-2xl mb-3"
                         style="background-color: {{ $cat->couleur }}15; color: {{ $cat->couleur }}">
                        <i class="{{ $cat->icone }}"></i>
                    </div>
                @else
                    <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center mb-3"
                         style="background-color: {{ $cat->couleur }}15; color: {{ $cat->couleur }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                @endif
                <h3 class="text-sm font-semibold text-gray-900 group-hover:text-gray-700 transition">
                    {{ $cat->libelle }}
                </h3>
                <p class="text-xs text-gray-400 mt-1">{{ $cat->annonces_count ?? 0 }} annonces</p>
            </a>
        @empty
            <div class="col-span-full text-center py-12 text-gray-400">
                Aucune catégorie pour le moment.
            </div>
        @endforelse
    </div>
</section>
