<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Board;
use Illuminate\Support\Facades\Auth;

class BoardController extends Controller
{
    public function show($gameId, $userId)
    {
        $authId = Auth::id();
        $board = Board::where('game_id', $gameId)
            ->where('user_id', $userId)
            ->firstOrFail();
        $playerIds = Board::where('game_id', $gameId)->pluck('user_id');
        if (!$playerIds->contains($authId)) {
            return response()->json(['message' => 'No tienes permiso para ver este tablero'], 403);
        }
        $isOwnBoard = $authId == $userId;
        $data = [
            'shots' => $board->shots,
            'ships' => $isOwnBoard ? $board->ships : null,
        ];
        return response()->json($data);
    }
}
