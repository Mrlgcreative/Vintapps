@extends('layouts.public')

@section('title', 'Tableau de bord vendeur')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Bonjour, {{ auth()->user()->boutique_name ?: auth()->user()->name }}</h1>
            <p class="text-sm text-gray-500 mt-1">Voici un aperçu de votre activité</p>
        </div>
        <a href="{{ route('vendeur.annonces.create') }}"
           class="px-4 py-2 bg-[#723EC3] text-white text-sm font-medium rounded-lg hover:bg-[#723EC3]/90 transition">
            + Nouvelle annonce
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Annonces</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_annonces'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Publiées</p>
            <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['annonces_publiees'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Vues</p>
            <p class="text-2xl font-bold text-[#333D6D] mt-1">{{ number_format($stats['vues_total'], 0, ',', ' ') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Messages non lus</p>
            <p class="text-2xl font-bold {{ $stats['messages_non_lus'] > 0 ? 'text-red-500' : 'text-gray-900' }} mt-1">{{ $stats['messages_non_lus'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Note</p>
            <div class="flex items-center gap-1 mt-1">
                <span class="text-2xl font-bold text-gray-900">{{ $stats['note_moyenne'] }}</span>
                <span class="text-xs text-gray-400">/5</span>
            </div>
            <p class="text-xs text-gray-400">{{ $stats['nb_avis'] }} avis</p>
        </div>
    </div>

    <div class="lg:grid lg:grid-cols-2 lg:gap-6">
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Dernières annonces</h2>
                <a href="{{ route('vendeur.annonces.index') }}" class="text-sm text-[#723EC3] hover:text-[#723EC3]/80 transition">Voir tout</a>
            </div>
            <div class="space-y-2">
                @forelse($recentes as $a)
                    <a href="{{ route('vendeur.annonces.edit', $a) }}" class="flex items-center justify-between bg-white rounded-xl shadow-sm border border-gray-100 p-3 hover:shadow-md transition">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $a->titre }}</p>
                            <p class="text-xs text-gray-400">{{ $a->categorie?->libelle }} &middot; {{ $a->vues }} vues</p>
                        </div>
                        <div class="text-right ml-3 flex-shrink-0">
                            <p class="text-sm font-bold text-[#723EC3]">{{ number_format($a->prix, 0, ',', ' ') }} {{ $a->devise === 'CDF' ? 'FC' : '$' }}</p>
                            <span class="text-xs {{ $a->statut === 'publiee' ? 'text-green-600' : 'text-gray-400' }}">{{ $a->statut === 'publiee' ? 'Publiée' : ucfirst($a->statut) }}</span>
                        </div>
                    </a>
                @empty
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
                        <p class="text-gray-400 text-sm">Aucune annonce pour le moment.</p>
                        <a href="{{ route('vendeur.annonces.create') }}" class="text-sm text-[#723EC3] hover:text-[#723EC3]/80 transition mt-2 inline-block">Créer ma première annonce</a>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mt-6 lg:mt-0">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Messages récents</h2>
                <a href="{{ route('messages.index') }}" class="text-sm text-[#723EC3] hover:text-[#723EC3]/80 transition">Voir tout</a>
            </div>
            <div class="space-y-2">
                @forelse($conversations as $conv)
                    @php
                        $interlocuteur = $conv->interlocuteur(auth()->id());
                        $dm = $conv->dernierMessage;
                        $nonLu = $conv->messages()->where('user_id', '!=', auth()->id())->where('lu', false)->count();
                    @endphp
                    <a href="{{ route('messages.show', $conv) }}" class="flex items-center gap-3 bg-white rounded-xl shadow-sm border border-gray-100 p-3 hover:shadow-md transition {{ $nonLu > 0 ? 'border-l-4 border-l-[#723EC3]' : '' }}">
                        <div class="w-8 h-8 rounded-full bg-[#333D6D]/10 flex items-center justify-center text-[#333D6D] font-bold text-xs flex-shrink-0 overflow-hidden">
                            @if($interlocuteur->avatar)
                                <img src="{{ Storage::url($interlocuteur->avatar) }}" alt="" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr($interlocuteur->name, 0, 2)) }}
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $interlocuteur->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $conv->annonce->titre }}</p>
                            @if($dm)
                                <p class="text-xs text-gray-500 truncate mt-0.5 {{ $nonLu > 0 ? 'font-semibold' : '' }}">{{ $dm->contenu }}</p>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
                        <p class="text-gray-400 text-sm">Aucun message.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
