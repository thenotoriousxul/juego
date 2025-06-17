<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GameHistoryController extends Controller
{
    public function show($gameId)
    {
        $game = Game::with(['boards.user', 'moves'])
            ->findOrFail($gameId);

        if (!$game->boards->contains('user_id', auth()->id())) {
            abort(403, 'No tienes permiso para ver este historial');
        }

        $moves = $game->moves->sortBy('created_at')->map(function ($move) use ($game) {
            $shooter = $game->boards->firstWhere('user_id', $move->user_id);
            $target = $game->boards->firstWhere('user_id', '!=', $move->user_id);
            
            return [
                'id' => $move->id,
                'position' => $move->position,
                'hit' => $move->hit,
                'shooter' => $shooter->user->name,
                'target' => $target->user->name,
                'created_at' => $move->created_at->format('H:i:s'),
            ];
        });

        return Inertia::render('GameHistoryView', [
            'game' => $game,
            'moves' => $moves->values(),
        ]);
    }
} 