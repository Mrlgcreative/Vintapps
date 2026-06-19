@extends('layouts.public')

@section('title', 'Mon portefeuille')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Mon portefeuille</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm text-gray-500 mb-1">Solde USD</p>
            <p class="text-3xl font-bold text-[#723EC3]">{{ number_format($wallet->balance_usd, 2, ',', ' ') }} $</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm text-gray-500 mb-1">Solde FC</p>
            <p class="text-3xl font-bold text-[#723EC3]">{{ number_format($wallet->balance_cdf, 2, ',', ' ') }} FC</p>
        </div>
    </div>

    <h2 class="text-lg font-semibold text-gray-900 mb-4">Historique des transactions</h2>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Date</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Type</th>
                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Motif</th>
                    <th class="text-right px-4 py-3 font-semibold text-gray-600">Montant</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($transactions as $t)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $t->type === 'credit' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                {{ $t->type === 'credit' ? 'Crédit' : 'Débit' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-700">{{ $t->motif }}</td>
                        <td class="px-4 py-3 text-right font-semibold {{ $t->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $t->type === 'credit' ? '+' : '-' }}{{ number_format($t->montant, 0, ',', ' ') }} {{ $t->devise === 'CDF' ? 'FC' : '$' }}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-12 text-center text-gray-500">Aucune transaction.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($transactions->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">{{ $transactions->links() }}</div>
        @endif
    </div>
</div>
@endsection
