@extends('layouts.public')

@section('title', 'Conversation')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="mb-4">
        <a href="{{ route('messages.index') }}" class="text-sm text-[#723EC3] hover:text-[#723EC3]/80 transition">&larr; Tous les messages</a>
    </div>

    @php $interlocuteur = $conversation->interlocuteur(auth()->id()); @endphp

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-[#333D6D]/10 flex items-center justify-center text-[#333D6D] font-bold text-sm flex-shrink-0 overflow-hidden">
                @if($interlocuteur->avatar)
                    <img src="{{ Storage::url($interlocuteur->avatar) }}" alt="" class="w-full h-full object-cover">
                @else
                    {{ strtoupper(substr($interlocuteur->name, 0, 2)) }}
                @endif
            </div>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-gray-900">{{ $interlocuteur->boutique_name ?: $interlocuteur->name }}</p>
                <a href="{{ route('annonces.show', $conversation->annonce) }}" class="text-xs text-gray-500 hover:text-[#723EC3] transition line-clamp-1">
                    {{ $conversation->annonce->titre }}
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 space-y-4 mb-4 max-h-[500px] overflow-y-auto" id="messages-box">
        @forelse($conversation->messages as $msg)
            <div class="flex {{ $msg->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[80%] {{ $msg->user_id === auth()->id() ? 'bg-[#723EC3] text-white' : 'bg-gray-100 text-gray-900' }} rounded-xl px-4 py-2.5 text-sm">
                    <p>{{ $msg->contenu }}</p>
                    <p class="text-xs mt-1 {{ $msg->user_id === auth()->id() ? 'text-white/60' : 'text-gray-400' }}">
                        {{ $msg->created_at->format('H:i') }}
                        @if($msg->user_id === auth()->id() && $msg->lu)
                            &middot; Lu
                        @endif
                    </p>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-400 text-sm py-8">Aucun message. Écrivez le premier message.</p>
        @endforelse
    </div>

    <form method="POST" action="{{ route('messages.envoyer', $conversation) }}" class="flex gap-2">
        @csrf
        <textarea name="contenu" rows="2" required
                  placeholder="Écrivez votre message..."
                  class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#723EC3] focus:border-[#723EC3] resize-none"></textarea>
        <button type="submit" class="px-5 py-2.5 bg-[#723EC3] text-white text-sm font-medium rounded-lg hover:bg-[#723EC3]/90 transition self-end">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
        </button>
    </form>
    <x-input-error :messages="$errors->get('contenu')" class="mt-1"/>
</div>

@push('scripts')
<script>
    const box = document.getElementById('messages-box');
    box.scrollTop = box.scrollHeight;
</script>
@endpush
@endsection
