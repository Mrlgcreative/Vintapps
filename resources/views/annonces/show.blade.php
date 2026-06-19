@extends('layouts.public')

@section('title', $annonce->titre)

@push('styles')
<style>
.gallery-thumb { transition: opacity .2s; }
.gallery-thumb:hover, .gallery-thumb.active { opacity: .7; }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <nav class="text-sm text-gray-400 mb-4">
        <a href="{{ route('annonces.index') }}" class="hover:text-[#723EC3] transition">Annonces</a>
        @if($annonce->categorie)
            <span class="mx-2">/</span>
            <a href="{{ route('annonces.index', ['categorie' => $annonce->categorie->slug]) }}" class="hover:text-[#723EC3] transition">{{ $annonce->categorie->libelle }}</a>
        @endif
        <span class="mx-2">/</span>
        <span class="text-gray-600">{{ Str::limit($annonce->titre, 40) }}</span>
    </nav>

    <div class="lg:grid lg:grid-cols-5 lg:gap-8">
        <div class="lg:col-span-3">
            @php
                $allPhotos = $annonce->photos->sortByDesc('est_couverture');
                $cover = $allPhotos->first();
            @endphp

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="aspect-[4/3] bg-gray-100 relative" id="main-photo-wrapper">
                    @if($cover)
                        <img id="main-photo" src="{{ Storage::url($cover->chemin_fichier) }}"
                             alt="{{ $annonce->titre }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100">
                        @auth
                            <button type="button" onclick="document.getElementById('signalement-form').classList.toggle('hidden')" class="text-xs text-gray-400 hover:text-red-500 transition flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21a9 9 0 113 5.5M3 21v-6m0 0h6"/>
                                </svg>
                                Signaler cette annonce
                            </button>
                            <form id="signalement-form" method="POST" action="{{ route('signalements.store', $annonce) }}" class="hidden mt-3 space-y-2">
                                @csrf
                                <select name="motif" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                                    <option value="">Sélectionnez un motif</option>
                                    <option value="Contenu inapproprié">Contenu inapproprié</option>
                                    <option value="Arnaque ou fraude">Arnaque ou fraude</option>
                                    <option value="Produit contrefait">Produit contrefait</option>
                                    <option value="Information trompeuse">Information trompeuse</option>
                                    <option value="Autre">Autre</option>
                                </select>
                                <textarea name="description" rows="2" placeholder="Description (optionnelle)" class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]"></textarea>
                                <button type="submit" class="px-3 py-1.5 text-xs font-medium text-white bg-red-500 rounded-lg hover:bg-red-600 transition">Envoyer le signalement</button>
                            </form>
                        @endauth
                    </div>
                @endif
                </div>

                @if($allPhotos->count() > 1)
                    <div class="flex gap-2 p-3 overflow-x-auto">
                        @foreach($allPhotos as $photo)
                            <button type="button" class="gallery-thumb flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 {{ $loop->first ? 'border-[#723EC3] active' : 'border-transparent' }}"
                                    data-src="{{ Storage::url($photo->chemin_fichier) }}">
                                <img src="{{ Storage::url($photo->chemin_fichier) }}" alt=""
                                     class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="lg:col-span-2 mt-6 lg:mt-0">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $annonce->titre }}</h1>

                <p class="text-3xl font-bold text-[#723EC3] mt-3">
                    {{ number_format($annonce->prix, 0, ',', ' ') }}
                    {{ $annonce->devise === 'CDF' ? 'FC' : '$' }}
                </p>

                <div class="flex flex-wrap gap-2 mt-4">
                    @auth
                        <form method="POST" action="{{ route('favoris.toggle', $annonce) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-medium border transition
                                {{ auth()->user()->estDansFavoris($annonce) ? 'bg-red-50 text-red-600 border-red-200' : 'bg-gray-50 text-gray-500 border-gray-200 hover:border-red-200 hover:text-red-500' }}">
                                <svg class="w-3.5 h-3.5" fill="{{ auth()->user()->estDansFavoris($annonce) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                {{ auth()->user()->estDansFavoris($annonce) ? 'Favori' : 'Favoris' }}
                            </button>
                        </form>
                    @endauth
                    @if($annonce->etat)
                        @php
                            $etats = [
                                'neuf' => ['label' => 'Neuf', 'color' => 'bg-green-50 text-green-700'],
                                'tres_bon_etat' => ['label' => 'Très bon état', 'color' => 'bg-blue-50 text-blue-700'],
                                'bon_etat' => ['label' => 'Bon état', 'color' => 'bg-yellow-50 text-yellow-700'],
                                'usage' => ['label' => 'Usage', 'color' => 'bg-orange-50 text-orange-700'],
                                'endommage' => ['label' => 'Endommagé', 'color' => 'bg-red-50 text-red-700'],
                            ];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $etats[$annonce->etat]['color'] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $etats[$annonce->etat]['label'] ?? $annonce->etat }}
                        </span>
                    @endif
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#FFF0D9] text-[#333D6D]">
                        {{ $annonce->created_at->format('d/m/Y') }}
                    </span>
                </div>

                @if($annonce->description)
                    <div class="mt-5">
                        <h2 class="text-sm font-semibold text-gray-700 mb-2">Description</h2>
                        <p class="text-gray-600 leading-relaxed whitespace-pre-line">{{ $annonce->description }}</p>
                    </div>
                @endif

                <hr class="my-5 border-gray-100">

                @if($annonce->user)
                    <div class="flex items-start gap-3">
                        <a href="{{ route('annonces.index', ['vendeur' => $annonce->user->id]) }}" class="w-10 h-10 rounded-full bg-[#333D6D]/10 flex items-center justify-center text-[#333D6D] font-bold text-sm flex-shrink-0 overflow-hidden hover:opacity-80 transition">
                            @if($annonce->user->avatar)
                                <img src="{{ Storage::url($annonce->user->avatar) }}" alt="" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr($annonce->user->name, 0, 2)) }}
                            @endif
                        </a>
                        <div class="min-w-0">
                            <a href="{{ route('annonces.index', ['vendeur' => $annonce->user->id]) }}" class="text-sm font-medium text-gray-900 hover:text-[#723EC3] transition">
                                {{ $annonce->user->boutique_name ?: $annonce->user->name }}
                            </a>
                            @if($annonce->user->boutique_name)
                                <p class="text-xs text-gray-400">{{ $annonce->user->name }}</p>
                            @endif
                            <a href="{{ route('evaluations.vendeur', $annonce->user) }}" class="inline-flex items-center gap-1 mt-1 hover:opacity-80 transition">
                                @php $moy = $annonce->user->noteMoyenne(); @endphp
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3 h-3 {{ $i <= $moy ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-xs text-gray-500">{{ $moy > 0 ? $moy : '—' }}</span>
                                <span class="text-xs text-gray-400">({{ $annonce->user->evaluationsRecues()->count() }})</span>
                            </a>
                            @if($annonce->user->bio)
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $annonce->user->bio }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-1">{{ $annonce->vues }} vues</p>
                        </div>
                    </div>

                    <div class="mt-4 space-y-2">
                        <form method="POST" action="{{ route('cart.add', $annonce) }}">
                            @csrf
                            <button type="submit" class="flex items-center justify-center w-full px-5 py-3 bg-[#723EC3] text-white font-medium rounded-lg hover:bg-[#723EC3]/90 transition text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                                </svg>
                                Ajouter au panier
                            </button>
                        </form>
                        @auth
                            @if(auth()->id() !== $annonce->user_id)
                                <form method="POST" action="{{ route('messages.contacter', $annonce) }}">
                                    @csrf
                                    <button type="submit" class="flex items-center justify-center w-full px-5 py-3 border border-[#723EC3] text-[#723EC3] font-medium rounded-lg hover:bg-[#723EC3]/5 transition text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                        Contacter le vendeur
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if($similaires->isNotEmpty())
        <section class="mt-12">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Annonces similaires</h2>
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($similaires as $s)
                    <a href="{{ route('annonces.show', $s) }}"
                       class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                        <div class="aspect-[4/3] bg-gray-100 overflow-hidden">
                            @if($s->url_couverture)
                                <img src="{{ $s->url_couverture }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-gray-900 text-sm line-clamp-1 group-hover:text-[#723EC3] transition">{{ $s->titre }}</h3>
                            <p class="text-sm font-bold text-[#723EC3] mt-1">{{ number_format($s->prix, 0, ',', ' ') }} {{ $s->devise === 'CDF' ? 'FC' : '$' }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>

@push('scripts')
<script>
document.querySelectorAll('.gallery-thumb').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.gallery-thumb').forEach(b => b.classList.remove('active', 'border-[#723EC3]'));
        this.classList.add('active', 'border-[#723EC3]');
        document.getElementById('main-photo').src = this.dataset.src;
    });
});
</script>
@endpush
@endsection
