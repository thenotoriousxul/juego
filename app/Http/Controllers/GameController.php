<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Board;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    // Listar todos los juegos (en los que participa el usuario o disponibles para unirse)
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

    // Crear un nuevo juego y asignar el usuario como primer jugador
    public function store(Request $request)
    {
        $userId = Auth::id();
        
        // Verificar si el usuario ya tiene una sala activa
        $activeGame = Game::whereHas('boards', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->where('status', 'waiting')->first();

        if ($activeGame) {
            return response()->json(['message' => 'Ya tienes una sala activa esperando jugadores'], 400);
        }

        return DB::transaction(function() use ($userId) {
            $game = Game::create(['status' => 'waiting']);
            // Generar barcos aleatorios para el tablero
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

    // Unirse a un juego existente (si hay espacio)
    public function join(Request $request, $gameId)
    {
        $userId = Auth::id();
        
        // Verificar si el usuario ya está en cualquier partida activa (waiting o playing)
        $activeGame = Game::whereHas('boards', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->whereIn('status', ['waiting', 'playing'])->first();

        if ($activeGame) {
            return response()->json([
                'message' => 'Ya estás en una partida activa. No puedes unirte a otra partida.',
                'gameId' => $activeGame->id
            ], 400);
        }

        $game = Game::findOrFail($gameId);
        if ($game->boards()->where('user_id', $userId)->exists()) {
            return response()->json(['message' => 'Ya estás en este juego'], 400);
        }
        if ($game->boards()->count() >= 2) {
            return response()->json(['message' => 'El juego ya tiene dos jugadores'], 400);
        }
        // Generar barcos aleatorios para el tablero
        $ships = $this->generateRandomShips();
        Board::create([
            'game_id' => $game->id,
            'user_id' => $userId,
            'ships' => $ships,
            'shots' => [],
        ]);
        
        $game->update(['status' => $game->boards()->count() == 2 ? 'playing' : 'waiting']);
        
        // Si la partida está completa, notificar a ambos jugadores
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

    // Ver detalle de un juego
    public function show($gameId)
    {
        $userId = Auth::id();
        $game = Game::with(['boards.user', 'moves'])->findOrFail($gameId);
        // Validar que el usuario sea jugador de la partida
        if (!$game->boards->pluck('user_id')->contains($userId)) {
            abort(403, 'No tienes permiso para ver esta partida');
        }
        
        // Si la petición es AJAX, devolver JSON
        if (request()->ajax()) {
            return response()->json([
                'game' => $game
            ]);
        }
        
        return inertia('GameDetail', [
            'game' => $game
        ]);
    }

    // Generar barcos aleatorios para el tablero (8x8, 15 barcos)
    private function generateRandomShips()
    {
        $ships = [];
        $positions = [];
        while (count($ships) < 15) {
            $row = chr(rand(65, 72)); // A-H
            $col = rand(1, 8);
            $pos = $row . $col;
            if (!in_array($pos, $positions)) {
                $ships[] = $pos;
                $positions[] = $pos;
            }
        }
        return $ships;
    }

    // Manejar la rendición de un jugador
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
}
