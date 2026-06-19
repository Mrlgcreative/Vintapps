@extends('layouts.admin')

@section('title', 'Mon profil')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">
    <h1 class="text-2xl font-bold text-gray-900">Mon profil</h1>

    {{-- Avatar + infos --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf @method('patch')

            <div class="flex items-center gap-6">
                <div class="relative">
                    <div class="w-20 h-20 rounded-full bg-[#723EC3]/10 flex items-center justify-center overflow-hidden">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="" class="w-full h-full object-cover">
                        @else
                            <span class="text-2xl font-bold text-[#723EC3]">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <label for="avatar" class="absolute -bottom-1 -right-1 w-7 h-7 bg-[#723EC3] text-white rounded-full flex items-center justify-center cursor-pointer hover:bg-[#723EC3]/90 transition shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </label>
                    <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden">
                </div>
                <div>
                    <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
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
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]"
                           placeholder="+243 XXX XXX XXX">
                    <x-input-error :messages="$errors->get('phone')" class="mt-1"/>
                </div>
            </div>

            <div>
                <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                <textarea name="bio" id="bio" rows="3"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]"
                          placeholder="Parlez-nous de vous...">{{ old('bio', $user->bio) }}</textarea>
                <x-input-error :messages="$errors->get('bio')" class="mt-1"/>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="px-6 py-2.5 bg-[#723EC3] text-white text-sm font-medium rounded-lg hover:bg-[#723EC3]/90 transition">
                    Enregistrer
                </button>
                @if(session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)"
                       class="text-sm text-green-600 font-medium">✓ Enregistré</p>
                @endif
            </div>
        </form>
    </div>

    {{-- Mot de passe --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf @method('put')

            <h2 class="text-lg font-semibold text-gray-900">Changer le mot de passe</h2>
            <p class="text-sm text-gray-500">Assurez-vous d'utiliser un mot de passe fort.</p>

            <div class="grid sm:grid-cols-3 gap-4">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Actuel</label>
                    <input type="password" name="current_password" id="current_password" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nouveau</label>
                    <input type="password" name="password" id="password" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                </div>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1"/>

            <div class="flex items-center gap-4">
                <button type="submit" class="px-6 py-2.5 bg-[#723EC3] text-white text-sm font-medium rounded-lg hover:bg-[#723EC3]/90 transition">
                    Mettre à jour
                </button>
                @if(session('status') === 'password-updated')
                    <p x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)"
                       class="text-sm text-green-600 font-medium">✓ Mis à jour</p>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
