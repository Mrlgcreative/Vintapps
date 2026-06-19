@extends('layouts.admin')

@section('title', 'Catégories')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Catégories</h1>
        <a href="{{ route('admin.categories.create') }}" class="px-4 py-2.5 bg-[#723EC3] text-white text-sm font-medium rounded-lg hover:bg-[#723EC3]/90 transition">
            + Nouvelle catégorie
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Libellé</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Slug</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Couleur</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Ordre</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Actif</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($categories as $cat)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $cat->libelle }}</td>
                        <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $cat->slug }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1.5 text-xs">
                                <span class="w-4 h-4 rounded-full border" style="background-color: {{ $cat->couleur }}"></span>
                                {{ $cat->couleur }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $cat->ordre }}</td>
                        <td class="px-4 py-3">
                            @if($cat->actif)
                                <span class="text-green-600 text-xs font-medium">Oui</span>
                            @else
                                <span class="text-red-500 text-xs font-medium">Non</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.categories.edit', $cat) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-[#723EC3] hover:bg-[#723EC3]/5 rounded-lg transition">Modifier</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" class="inline"
                                  onsubmit="return confirm('Supprimer cette catégorie ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 rounded-lg transition">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">Aucune catégorie.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($categories->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">{{ $categories->links() }}</div>
        @endif
    </div>
@endsection
