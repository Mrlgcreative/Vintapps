<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index()
    {
        $wallet = auth()->user()->wallet;
        $transactions = $wallet->transactions()->latest()->paginate(15);

        return view('wallet.index', compact('wallet', 'transactions'));
    }
}
