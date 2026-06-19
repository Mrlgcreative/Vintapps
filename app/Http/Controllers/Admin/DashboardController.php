<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'vendeurs' => User::where('role', 'vendeur')->count(),
            'acheteurs' => User::where('role', 'acheteur')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
