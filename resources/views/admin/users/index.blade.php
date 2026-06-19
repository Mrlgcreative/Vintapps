@extends('layouts.admin')

@section('title', 'Utilisateurs')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Utilisateurs</h1>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.users') }}" class="mb-6 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Rechercher par nom ou email..."
               class="flex-1 min-w-[200px] px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
        <select name="role"
                class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
            <option value="">Tous les rôles</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="vendeur" {{ request('role') === 'vendeur' ? 'selected' : '' }}>Vendeur</option>
            <option value="acheteur" {{ request('role') === 'acheteur' ? 'selected' : '' }}>Acheteur</option>
        </select>
        <button type="submit" class="px-5 py-2.5 bg-[#723EC3] text-white text-sm font-medium rounded-lg hover:bg-[#723EC3]/90 transition">
            Filtrer
        </button>
        @if(request()->anyFilled(['search', 'role']))
            <a href="{{ route('admin.users') }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                Réinitialiser
            </a>
        @endif
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Nom</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Email</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Rôle</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Inscrit le</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium
                                    {{ $user->role === 'admin' ? 'bg-[#333D6D]/10 text-[#333D6D]' : '' }}
                                    {{ $user->role === 'vendeur' ? 'bg-[#FFCF95]/30 text-[#333D6D]' : '' }}
                                    {{ $user->role === 'acheteur' ? 'bg-gray-100 text-gray-600' : '' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-[#723EC3] hover:bg-[#723EC3]/5 rounded-lg transition">
                                    Modifier
                                </a>
                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline"
                                          onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 rounded-lg transition">
                                            Supprimer
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-gray-500">
                                Aucun utilisateur trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
