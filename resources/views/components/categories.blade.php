@props([
    'categories' => [],
    'titre' => 'Catégories',
])

@php
    $icones = [
        'fas fa-mobile-alt' => '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>',
        'fas fa-tshirt' => '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 6l5-3 5 3v12a2 2 0 01-2 2H9a2 2 0 01-2-2V6z"/></svg>',
        'fas fa-tv' => '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
        'fas fa-home' => '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',
        'fas fa-futbol' => '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>',
        'fas fa-car' => '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 10h-2m8 2a2 2 0 01-2 2H4a2 2 0 01-2-2v-3a2 2 0 012-2h1l2-3h8l2 3h1a2 2 0 012 2v3zm-3 4v2m-8-2v2"/></svg>',
        'fas fa-gem' => '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3l2 2h10l2-2M5 3l2 14 5 4 5-4 2-14M5 3h14"/></svg>',
        'fas fa-building' => '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
    ];
@endphp

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <div class="text-center mb-10">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $titre }}</h2>
        <p class="mt-2 text-gray-500">Explorez nos catégories d'articles</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
        @forelse($categories as $cat)
            <a href="{{ route('annonces.index', ['categorie' => $cat->slug]) }}"
               class="group bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                <div class="w-14 h-14 mx-auto rounded-xl flex items-center justify-center mb-3"
                     style="background-color: {{ $cat->couleur }}12; color: {{ $cat->couleur }}">
                    {!! $icones[$cat->icone] ?? '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>' !!}
                </div>
                <h3 class="text-sm font-semibold text-gray-900 group-hover:text-[#723EC3] transition">
                    {{ $cat->libelle }}
                </h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ $cat->annonces_count ?? 0 }} annonces</p>
            </a>
        @empty
            <div class="col-span-full text-center py-12 text-gray-400">
                Aucune catégorie pour le moment.
            </div>
        @endforelse
    </div>
</section>
