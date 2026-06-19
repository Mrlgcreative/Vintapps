@extends('layouts.public')

@section('title', 'Évaluations de ' . ($user->boutique_name ?: $user->name))

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex items-center gap-4 mb-6">
        <div class="w-14 h-14 rounded-full bg-[#333D6D]/10 flex items-center justify-center text-[#333D6D] font-bold text-lg flex-shrink-0 overflow-hidden">
            @if($user->avatar)
                <img src="{{ Storage::url($user->avatar) }}" alt="" class="w-full h-full object-cover">
            @else
                {{ strtoupper(substr($user->name, 0, 2)) }}
            @endif
        </div>
        <div>
            <h1 class="text-xl font-bold text-gray-900">{{ $user->boutique_name ?: $user->name }}</h1>
            <div class="flex items-center gap-2 mt-1">
                <div class="flex">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 {{ $i <= $moyenne ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                <span class="text-sm font-medium text-gray-700">{{ $moyenne }}</span>
                <span class="text-sm text-gray-400">({{ $total }} avis)</span>
            </div>
        </div>
    </div>

    @if($evaluations->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <p class="text-gray-500">Aucune évaluation pour le moment.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($evaluations as $ev)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-[#333D6D]/10 flex items-center justify-center text-[#333D6D] font-bold text-xs flex-shrink-0 overflow-hidden">
                                @if($ev->acheteur->avatar)
                                    <img src="{{ Storage::url($ev->acheteur->avatar) }}" alt="" class="w-full h-full object-cover">
                                @else
                                    {{ strtoupper(substr($ev->acheteur->name, 0, 2)) }}
                                @endif
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $ev->acheteur->name }}</span>
                        </div>
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-3.5 h-3.5 {{ $i <= $ev->note ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                    </div>
                    @if($ev->commentaire)
                        <p class="text-sm text-gray-600 mt-2">{{ $ev->commentaire }}</p>
                    @endif
                    <p class="text-xs text-gray-400 mt-2">{{ $ev->created_at->format('d/m/Y') }}</p>
                </div>
            @endforeach
        </div>

        @if($evaluations->hasPages())
            <div class="mt-6">{{ $evaluations->links() }}</div>
        @endif
    @endif
</div>
@endsection
