@extends('layouts.public')

@section('title', 'Mon profil')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <h1 class="text-2xl font-bold text-gray-900">Mon profil</h1>

    @if(session('success'))
        <div class="px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">{{ session('error') }}</div>
    @endif

    {{-- Avatar + infos générales --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
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
                    <p class="text-xs text-gray-400 mt-0.5">
                        @if($user->estVendeur()) Vendeur
                        @elseif($user->estAdmin()) Administrateur
                        @else Acheteur @endif
                    </p>
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
                @if($user->estVendeur())
                <div>
                    <label for="boutique_name" class="block text-sm font-medium text-gray-700 mb-1">Nom de la boutique</label>
                    <input type="text" name="boutique_name" id="boutique_name" value="{{ old('boutique_name', $user->boutique_name) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]"
                           placeholder="Nom de votre boutique">
                    <x-input-error :messages="$errors->get('boutique_name')" class="mt-1"/>
                </div>
                @endif
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
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe actuel</label>
                    <input type="password" name="current_password" id="current_password" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3]">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
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
                       class="text-sm text-green-600 font-medium">✓ Mot de passe mis à jour</p>
                @endif
            </div>
        </form>
    </div>

    {{-- Devenir vendeur --}}
    @if($user->estAcheteur())
    <div class="bg-gradient-to-r from-[#FFF0D9] to-[#FFCF95]/30 rounded-xl shadow-sm border border-[#FFCF95]/50 p-6">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-full bg-[#723EC3]/10 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-[#723EC3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h2 class="text-lg font-semibold text-gray-900">Devenir vendeur</h2>
                <p class="text-sm text-gray-600 mt-1">Publiez vos articles et vendez-les sur Vintapp. Aucune commission, zéro frais.</p>
                <form method="POST" action="{{ route('profile.devenir-vendeur') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="px-6 py-2.5 bg-[#723EC3] text-white text-sm font-semibold rounded-lg hover:bg-[#723EC3]/90 transition">
                        Activer mon compte vendeur
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Suppression --}}
    <div class="bg-white rounded-xl shadow-sm border border-red-100 p-6">
        <h2 class="text-lg font-semibold text-red-600">Supprimer le compte</h2>
        <p class="text-sm text-gray-500 mt-1">Cette action est irréversible.</p>
        <form method="POST" action="{{ route('profile.destroy') }}" class="mt-4"
              onsubmit="return confirm('Voulez-vous vraiment supprimer votre compte ?')">
            @csrf @method('delete')
            <div class="flex items-center gap-3">
                <input type="password" name="password" placeholder="Votre mot de passe"
                       class="max-w-xs px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                <button type="submit" class="px-6 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition">
                    Supprimer
                </button>
            </div>
            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1"/>
        </form>
    </div>
</div>
@endsection
