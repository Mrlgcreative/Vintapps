@extends('layouts.public')

@section('title', 'Paiement en cours')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-center">
    <div id="loading-spinner" class="w-16 h-16 mx-auto mb-4 rounded-full bg-yellow-100 flex items-center justify-center animate-pulse">
        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 mb-2">Paiement en attente</h1>
    <p class="text-gray-500 mb-2">Commande : <strong>{{ $commande->numero }}</strong></p>

    @if(session('warning'))
        <div class="max-w-sm mx-auto mb-4 px-4 py-3 bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm rounded-lg">{{ session('warning') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6 text-left max-w-sm mx-auto">
        <div class="flex justify-between text-sm mb-2">
            <span class="text-gray-600">Montant</span>
            <span class="font-bold text-[#723EC3]">{{ number_format($paiement->montant, 0, ',', ' ') }} {{ $paiement->devise === 'CDF' ? 'FC' : '$' }}</span>
        </div>
        <div class="flex justify-between text-sm mb-2">
            <span class="text-gray-600">Opérateur</span>
            <span class="font-medium text-gray-900">{{ strtoupper($paiement->operateur) }}</span>
        </div>
        <div class="flex justify-between text-sm mb-2">
            <span class="text-gray-600">Téléphone</span>
            <span class="font-medium text-gray-900">{{ $paiement->telephone }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-600">Référence</span>
            <span class="font-medium text-gray-900">{{ $paiement->reference }}</span>
        </div>
    </div>

    @php $maishaConfigured = app(\App\Services\MaishaPay::class)->isConfigured(); @endphp

    @if($maishaConfigured)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-left text-sm text-blue-800 max-w-sm mx-auto">
            <p class="font-medium mb-1">Paiement Mobile Money</p>
            <p class="text-blue-700">
                Une demande de paiement a été envoyée à <strong>{{ $paiement->telephone }}</strong>
                ({{ strtoupper($paiement->operateur) }}).
                Entrez votre code PIN sur votre téléphone pour autoriser le paiement.
            </p>
            <p class="text-blue-600 text-xs mt-2" id="poll-status">En attente de confirmation...</p>
        </div>
    @else
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-left text-sm text-blue-800 max-w-sm mx-auto">
            <p class="font-medium mb-1">Mode démonstration</p>
            <p class="text-blue-700">
                Vous allez recevoir une notification <strong>{{ strtoupper($paiement->operateur) }}</strong> sur votre téléphone.
                Entrez votre code PIN pour confirmer le paiement, puis cliquez sur le bouton ci-dessous.
            </p>
        </div>
    @endif

    <form method="POST" action="{{ route('checkout.confirmer-paiement', [$commande, $paiement]) }}" class="inline">
        @csrf
        <button type="submit" class="px-8 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
            J'ai confirmé le paiement
        </button>
    </form>
    <p class="text-xs text-gray-400 mt-3">Si vous n'avez pas reçu la notification, réessayez dans quelques secondes.</p>
</div>

@if($maishaConfigured)
    <script>
        (function() {
            let attempts = 0;
            const maxAttempts = 30;
            const statusEl = document.getElementById('poll-status');
            const spinner = document.getElementById('loading-spinner');

            function checkStatus() {
                attempts++;
                fetch('{{ route("payments.maishapay.status", $paiement->reference) }}')
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'paye') {
                            statusEl.textContent = 'Paiement confirmé ! Redirection...';
                            statusEl.className = 'text-green-600 text-xs mt-2 font-medium';
                            spinner.className = 'w-16 h-16 mx-auto mb-4 rounded-full bg-green-100 flex items-center justify-center';
                            spinner.innerHTML = '<svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                            setTimeout(() => { window.location.href = '{{ route("checkout.confirmation", $commande) }}'; }, 1500);
                            return;
                        }
                        if (data.status === 'echoue') {
                            statusEl.textContent = 'Paiement échoué. Cliquez sur le bouton pour réessayer.';
                            statusEl.className = 'text-red-600 text-xs mt-2 font-medium';
                            return;
                        }
                        if (attempts < maxAttempts) {
                            setTimeout(checkStatus, 3000);
                        } else {
                            statusEl.textContent = 'Toujours en attente ? Cliquez sur le bouton ci-dessous.';
                        }
                    })
                    .catch(() => {
                        if (attempts < maxAttempts) {
                            setTimeout(checkStatus, 3000);
                        }
                    });
            }

            if (statusEl) setTimeout(checkStatus, 3000);
        })();
    </script>
@endif
@endsection
