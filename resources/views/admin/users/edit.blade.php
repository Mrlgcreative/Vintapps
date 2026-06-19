@extends('layouts.admin')

@section('title', 'Modifier l\'utilisateur')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.users') }}" class="text-sm text-[#723EC3] hover:text-[#723EC3]/80 transition">&larr; Retour aux utilisateurs</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Modifier {{ $user->name }}</h1>
    </div>

    <div class="max-w-lg bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PATCH')

            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                    <x-input-error :messages="$errors->get('name')" class="mt-1"/>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                    <x-input-error :messages="$errors->get('email')" class="mt-1"/>
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                    <select name="role" id="role" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                        <option value="acheteur" {{ old('role', $user->role) === 'acheteur' ? 'selected' : '' }}>Acheteur</option>
                        <option value="vendeur" {{ old('role', $user->role) === 'vendeur' ? 'selected' : '' }}>Vendeur</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-1"/>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-3">
                <button type="submit" class="px-6 py-2.5 bg-[#723EC3] text-white text-sm font-medium rounded-lg hover:bg-[#723EC3]/90 transition">
                    Enregistrer
                </button>
                <a href="{{ route('admin.users') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                    Annuler
                </a>
            </div>
        </form>
    </div>
@endsection
