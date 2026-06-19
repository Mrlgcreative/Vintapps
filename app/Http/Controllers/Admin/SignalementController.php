<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Signalement;
use Illuminate\Http\Request;

class SignalementController extends Controller
{
    public function index(Request $request)
    {
        $query = Signalement::with(['annonce', 'signaléPar']);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $signalements = $query->latest()->paginate(20);

        return view('admin.signalements.index', compact('signalements'));
    }

    public function resoudre(Signalement $signalement)
    {
        $signalement->update(['statut' => 'resolu']);

        return back()->with('success', 'Signalement marqué comme résolu.');
    }

    public function rejeter(Signalement $signalement)
    {
        $signalement->update(['statut' => 'rejete']);

        return back()->with('success', 'Signalement rejeté.');
    }
}
