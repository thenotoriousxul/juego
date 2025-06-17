<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Board;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $games = Game::with(['boards.user'])
            ->whereHas('boards', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->orWhere('status', 'waiting')
            ->orderByDesc('created_at')
            ->get();
        return inertia('GameList', [
            'games' => $games
        ]);
    }

    public function store(Request $request)
    {
        $userId = Auth::id();
        
        $activeGame = Game::whereHas('boards', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->whereIn('status', ['waiting', 'playing'])->first();

        if ($activeGame) {
            return response()->json([
                'message' => 'Ya tienes una partida activa. No puedes crear otra hasta que termines o canceles la actual.',
                'gameId' => $activeGame->id
            ], 400);
        }

        return DB::transaction(function() use ($userId) {
            $game = Game::create(['status' => 'waiting']);
            $ships = $this->generateRandomShips();
            Board::create([
                'game_id' => $game->id,
                'user_id' => $userId,
                'ships' => $ships,
                'shots' => [],
            ]);
            return response()->json([
                'id' => $game->id,
                'message' => 'Partida creada exitosamente'
            ], 201);
        });
    }

    public function join(Request $request, $gameId)
    {
        $userId = Auth::id();
        
        $activeGame = Game::whereHas('boards', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->whereIn('status', ['waiting', 'playing'])->first();

        if ($activeGame) {
            return response()->json([
                'message' => 'Ya tienes una partida activa. No puedes unirte a otra partida hasta que termines o canceles la actual.',
                'gameId' => $activeGame->id
            ], 400);
        }

        $game = Game::findOrFail($gameId);
        
        if ($game->status !== 'waiting') {
            return response()->json(['message' => 'Esta partida ya no está disponible para unirse'], 400);
        }
        
        if ($game->boards()->where('user_id', $userId)->exists()) {
            return response()->json(['message' => 'Ya estás en este juego'], 400);
        }
        
        if ($game->boards()->count() >= 2) {
            return response()->json(['message' => 'El juego ya tiene dos jugadores'], 400);
        }
        
        $ships = $this->generateRandomShips();
        Board::create([
            'game_id' => $game->id,
            'user_id' => $userId,
            'ships' => $ships,
            'shots' => [],
        ]);
        
        $game->update(['status' => $game->boards()->count() == 2 ? 'playing' : 'waiting']);
        
        if ($game->status === 'playing') {
            $game->load('boards.user');
            $players = $game->boards->pluck('user.name')->join(' y ');
            session()->flash('game_started', "¡La partida ha comenzado! Jugadores: $players");
        }
        
        return response()->json([
            'game' => $game,
            'message' => $game->status === 'playing' ? '¡La partida ha comenzado!' : 'Te has unido a la partida'
        ]);
    }

    public function show($gameId)
    {
        $userId = Auth::id();
        $game = Game::with(['boards.user', 'moves.user'])->findOrFail($gameId);
        if (!$game->boards->pluck('user_id')->contains($userId)) {
            abort(403, 'No tienes permiso para ver esta partida');
        }
        
        $moves = $game->moves->sortBy('created_at')->map(function ($move) {
            return [
                'id' => $move->id,
                'player_id' => $move->user_id,
                'player_name' => $move->user ? $move->user->name : 'Desconocido',
                'position' => $move->position,
                'result' => $move->hit ? 'hit' : 'miss',
                'hit' => $move->hit,
                'created_at' => $move->created_at->format('H:i:s'),
            ];
        })->values();
        
        if (request()->ajax()) {
            return response()->json([
                'game' => [
                    'id' => $game->id,
                    'status' => $game->status,
                    'winner_id' => $game->winner_id,
                    'boards' => $game->boards,
                    'moves' => $moves,
                ]
            ]);
        }
        
        return inertia('GameDetail', [
            'game' => [
                'id' => $game->id,
                'status' => $game->status,
                'winner_id' => $game->winner_id,
                'boards' => $game->boards,
                'moves' => $moves,
            ]
        ]);
    }

    private function generateRandomShips()
    {
        $ships = [];
        $positions = [];
        while (count($ships) < 15) {
            $row = chr(rand(65, 72));
            $col = rand(1, 8);
            $pos = $row . $col;
            if (!in_array($pos, $positions)) {
                $ships[] = $pos;
                $positions[] = $pos;
            }
        }
        return $ships;
    }

    public function surrender($gameId)
    {
        $userId = auth()->id();
        $game = Game::with('boards')->findOrFail($gameId);
        
        if (!$game->boards->contains('user_id', $userId)) {
            return response()->json(['message' => 'No eres un jugador de esta partida'], 403);
        }
        
        if ($game->status !== 'playing') {
            return response()->json(['message' => 'La partida no está en curso'], 400);
        }
        
        $rivalBoard = $game->boards->where('user_id', '!=', $userId)->first();
        
        $game->status = 'finished';
        $game->winner_id = $rivalBoard->user_id;
        $game->save();
        
        return response()->json(['message' => 'Te has rendido. La partida ha terminado.']);
    }

    public function destroy($gameId)
    {
        $userId = auth()->id();
        $game = Game::with('boards')->findOrFail($gameId);
        
        if (!$game->boards->contains('user_id', $userId)) {
            return response()->json(['message' => 'No eres un jugador de esta partida'], 403);
        }
        
        if ($game->status !== 'waiting') {
            return response()->json(['message' => 'Solo se pueden cancelar partidas en espera'], 400);
        }
        
        if ($game->boards->count() > 1) {
            return response()->json(['message' => 'No se puede cancelar una partida con dos jugadores'], 400);
        }
        
        $game->delete();
        
        return response()->json(['message' => 'Partida cancelada exitosamente']);
    }

    public function cancel($id)
    {
        $game = Game::findOrFail($id);
        if ($game->status === 'waiting') {
            $game->status = 'cancelled';
            $game->save();
        }
        return redirect()->route('games.index');
    }
}
