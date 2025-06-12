<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Move;
use App\Models\Board;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MoveController extends Controller
{
    // Realizar un disparo
    public function store(Request $request, $gameId)
    {
        $userId = Auth::id();
        $game = Game::with('boards')->findOrFail($gameId);
        // Validar que el usuario sea jugador de la partida
        if (!$game->boards->pluck('user_id')->contains($userId)) {
            return response()->json(['message' => 'No tienes permiso para jugar en esta partida'], 403);
        }
        if ($game->status !== 'playing') {
            return response()->json(['message' => 'La partida no está activa'], 400);
        }
        // Validar que sea el turno del usuario (alternancia por cantidad de movimientos)
        $movesCount = $game->moves()->count();
        $turnUserId = $game->boards[$movesCount % 2]->user_id;
        if ($userId !== $turnUserId) {
            return response()->json(['message' => 'No es tu turno'], 403);
        }
        $position = strtoupper($request->input('position'));
        // Validar que la posición sea válida
        if (!preg_match('/^[A-H][1-8]$/', $position)) {
            return response()->json(['message' => 'Posición inválida. Usa formato como A3.'], 422);
        }
        // Determinar el rival
        $rivalBoard = $game->boards->where('user_id', '!=', $userId)->first();
        if (!$rivalBoard) {
            return response()->json(['message' => 'No hay rival'], 400);
        }
        // Verificar si ya se disparó a esa posición
        if (in_array($position, $rivalBoard->shots ?? [])) {
            return response()->json(['message' => 'Ya disparaste a esa posición'], 409);
        }
        // Verificar si es acierto
        $hit = in_array($position, $rivalBoard->ships ?? []);
        // Registrar el disparo
        return DB::transaction(function() use ($game, $rivalBoard, $userId, $position, $hit) {
            $shots = $rivalBoard->shots ?? [];
            $shots[] = $position;
            $rivalBoard->shots = $shots;
            $rivalBoard->save();
            \App\Models\Move::create([
                'game_id' => $game->id,
                'user_id' => $userId,
                'position' => $position,
                'hit' => $hit,
            ]);
            // Verificar si el juego terminó
            $shipsLeft = array_diff($rivalBoard->ships ?? [], $shots);
            if (empty($shipsLeft)) {
                $game->status = 'finished';
                $game->winner_id = $userId;
                $game->save();
            }
            return response()->json(['hit' => $hit, 'finished' => empty($shipsLeft)]);
        });
    }

    // Mostrar historial de movimientos de un juego
    public function history($gameId)
    {
        $userId = Auth::id();
        $game = Game::with('boards')->findOrFail($gameId);
        if (!$game->boards->pluck('user_id')->contains($userId)) {
            return response()->json(['message' => 'No tienes permiso para ver el historial de esta partida'], 403);
        }
        $moves = \App\Models\Move::where('game_id', $gameId)->orderBy('created_at')->get();
        return response()->json($moves);
    }
}
