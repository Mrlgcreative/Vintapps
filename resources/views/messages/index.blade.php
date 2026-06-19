@extends('layouts.public')

@section('title', 'Messages')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Mes messages</h1>
        @if($nonLu > 0)
            <span class="text-xs bg-red-500 text-white px-2 py-1 rounded-full">{{ $nonLu }} non lu(s)</span>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg">{{ session('success') }}</div>
    @endif

    @if($conversations->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <p class="text-gray-500">Aucune conversation pour le moment.</p>
        </div>
    @else
        <div class="space-y-2">
            @foreach($conversations as $conv)
                @php
                    $interlocuteur = $conv->interlocuteur(auth()->id());
                    $dm = $conv->dernierMessage;
                    $nonLuConv = $conv->messages()->where('user_id', '!=', auth()->id())->where('lu', false)->count();
                @endphp
                <a href="{{ route('messages.show', $conv) }}"
                   class="flex items-start gap-3 bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition {{ $nonLuConv > 0 ? 'border-l-4 border-l-[#723EC3]' : '' }}">
                    <div class="w-10 h-10 rounded-full bg-[#333D6D]/10 flex items-center justify-center text-[#333D6D] font-bold text-sm flex-shrink-0 overflow-hidden">
                        @if($interlocuteur->avatar)
                            <img src="{{ Storage::url($interlocuteur->avatar) }}" alt="" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($interlocuteur->name, 0, 2)) }}
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-gray-900">{{ $interlocuteur->boutique_name ?: $interlocuteur->name }}</span>
                            @if($dm)
                                <span class="text-xs text-gray-400">{{ $dm->created_at->diffForHumans() }}</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ $conv->annonce->titre }}</p>
                        @if($dm)
                            <p class="text-sm text-gray-600 mt-1 line-clamp-1 {{ $nonLuConv > 0 ? 'font-semibold' : '' }}">{{ $dm->contenu }}</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
