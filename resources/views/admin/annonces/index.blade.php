@extends('layouts.admin')

@section('title', 'Annonces')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Annonces</h1>
    </div>

    <form method="GET" action="{{ route('admin.annonces') }}" class="mb-6 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Rechercher..."
               class="flex-1 min-w-[200px] px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
        <select name="statut"
                class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
            <option value="">Tous les statuts</option>
            <option value="publiee" {{ request('statut') === 'publiee' ? 'selected' : '' }}>Publiée</option>
            <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
            <option value="refusee" {{ request('statut') === 'refusee' ? 'selected' : '' }}>Refusée</option>
            <option value="vendue" {{ request('statut') === 'vendue' ? 'selected' : '' }}>Vendue</option>
            <option value="archivee" {{ request('statut') === 'archivee' ? 'selected' : '' }}>Archivée</option>
        </select>
        <button type="submit" class="px-5 py-2.5 bg-[#723EC3] text-white text-sm font-medium rounded-lg hover:bg-[#723EC3]/90 transition">Filtrer</button>
        @if(request()->anyFilled(['search', 'statut']))
            <a href="{{ route('admin.annonces') }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">Réinitialiser</a>
        @endif
    </form>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Titre</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Vendeur</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Prix</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Statut</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Date</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($annonces as $a)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-900 max-w-[200px] truncate">{{ $a->titre }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $a->user->name }}</td>
                        <td class="px-4 py-3 text-gray-900 font-medium">{{ number_format($a->prix, 0, ',', ' ') }} {{ $a->devise === 'CDF' ? 'FC' : '$' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium
                                {{ $a->statut === 'publiee' ? 'bg-green-50 text-green-700' : '' }}
                                {{ $a->statut === 'en_attente' ? 'bg-yellow-50 text-yellow-700' : '' }}
                                {{ $a->statut === 'refusee' ? 'bg-red-50 text-red-700' : '' }}
                                {{ $a->statut === 'vendue' ? 'bg-blue-50 text-blue-700' : '' }}
                                {{ $a->statut === 'archivee' ? 'bg-gray-100 text-gray-600' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $a->statut)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $a->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                @if($a->statut !== 'publiee')
                                    <form method="POST" action="{{ route('admin.annonces.statut', $a) }}" class="inline" title="Publier">
                                        @csrf
                                        <input type="hidden" name="statut" value="publiee">
                                        <button type="submit" class="inline-flex items-center px-2 py-1.5 text-xs font-medium text-green-600 hover:bg-green-50 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                    </form>
                                @endif
                                @if($a->statut !== 'refusee')
                                    <form method="POST" action="{{ route('admin.annonces.statut', $a) }}" class="inline" title="Refuser">
                                        @csrf
                                        <input type="hidden" name="statut" value="refusee">
                                        <button type="submit" class="inline-flex items-center px-2 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.annonces.edit', $a) }}" class="inline-flex items-center px-2 py-1.5 text-xs font-medium text-[#723EC3] hover:bg-[#723EC3]/5 rounded-lg transition" title="Modifier">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.annonces.destroy', $a) }}" class="inline"
                                      onsubmit="return confirm('Supprimer cette annonce ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-2 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 rounded-lg transition" title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">Aucune annonce.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($annonces->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">{{ $annonces->links() }}</div>
        @endif
    </div>
@endsection
