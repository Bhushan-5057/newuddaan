<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bonanza;
use App\Models\BonanzaAchiever;
use Illuminate\Support\Facades\Auth;

class UserBonanzaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Fetch achievements for logged-in user
        $achievements = BonanzaAchiever::with('bonanza')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // Optionally fetch all active bonanzas for display
        $activeBonanzas = Bonanza::whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->get();

        return view('user.bonanza.index', compact('achievements', 'activeBonanzas'));
    }
}
