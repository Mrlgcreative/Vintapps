@extends('layouts.admin')

@section('title', 'Signalements')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Signalements</h1>
</div>

<form method="GET" action="{{ route('admin.signalements') }}" class="mb-6 flex flex-wrap gap-3">
    <select name="statut" class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
        <option value="">Tous</option>
        <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
        <option value="resolu" {{ request('statut') === 'resolu' ? 'selected' : '' }}>Résolu</option>
        <option value="rejete" {{ request('statut') === 'rejete' ? 'selected' : '' }}>Rejeté</option>
    </select>
    <button type="submit" class="px-5 py-2.5 bg-[#723EC3] text-white text-sm font-medium rounded-lg hover:bg-[#723EC3]/90 transition">Filtrer</button>
    @if(request()->filled('statut'))
        <a href="{{ route('admin.signalements') }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">Réinitialiser</a>
    @endif
</form>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-4 py-3 font-semibold text-gray-600">Annonce</th>
                <th class="text-left px-4 py-3 font-semibold text-gray-600">Signalé par</th>
                <th class="text-left px-4 py-3 font-semibold text-gray-600">Motif</th>
                <th class="text-left px-4 py-3 font-semibold text-gray-600">Statut</th>
                <th class="text-left px-4 py-3 font-semibold text-gray-600">Date</th>
                <th class="text-right px-4 py-3 font-semibold text-gray-600">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($signalements as $s)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3">
                        <a href="{{ route('annonces.show', $s->annonce) }}" class="text-gray-900 font-medium hover:text-[#723EC3] transition line-clamp-1">{{ $s->annonce->titre }}</a>
                        <p class="text-xs text-gray-400">{{ $s->annonce->user->name }}</p>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $s->signaléPar->name }}</td>
                    <td class="px-4 py-3 max-w-[200px]">
                        <p class="text-gray-900 font-medium">{{ $s->motif }}</p>
                        @if($s->description)
                            <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $s->description }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium
                            {{ $s->statut === 'en_attente' ? 'bg-yellow-50 text-yellow-700' : '' }}
                            {{ $s->statut === 'resolu' ? 'bg-green-50 text-green-700' : '' }}
                            {{ $s->statut === 'rejete' ? 'bg-gray-100 text-gray-600' : '' }}">
                            {{ ucfirst($s->statut) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $s->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        @if($s->statut === 'en_attente')
                            <form method="POST" action="{{ route('admin.signalements.resoudre', $s) }}" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-green-600 hover:bg-green-50 rounded-lg transition">Résoudre</button>
                            </form>
                            <form method="POST" action="{{ route('admin.signalements.rejeter', $s) }}" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 rounded-lg transition">Rejeter</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">Aucun signalement.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($signalements->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">{{ $signalements->links() }}</div>
    @endif
</div>
@endsection
