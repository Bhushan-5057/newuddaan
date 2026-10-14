<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RankRewardBonus;
use Illuminate\Http\Request;

class RankRewardController extends Controller
{
    /**
     * Render the unified View & Update Excel grid interface.
     */
    public function index()
    {
        // Fetch all ranks ordered by sequence to display in the view grid
        $ranks = RankRewardBonus::orderBy('id', 'asc')->get();
        
        return view('admin.rewards.index', compact('ranks'));
    }

    /**
     * Handle the inline cell modifications asynchronously.
     */
    public function updateRankField(Request $request)
    {
        $request->validate([
            'id'     => 'required|exists:rank_reward_bonuses,id',
            'fields' => 'required|array'
        ]);

        $rank = RankRewardBonus::find($request->id);
        
        // Extract and map row values securely
        foreach ($request->fields as $field => $value) {
            // Removed 'name' from here so it can be updated
            if (!in_array($field, ['id', 'slug'])) {
                $rank->{$field} = $value;
            }
        }
        
        $rank->save();

        return response()->json([
            'success' => true, 
            'message' => 'Row updated successfully!'
        ]);
    }
}