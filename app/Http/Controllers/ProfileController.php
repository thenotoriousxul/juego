<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Game;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function stats()
    {
        $userId = auth()->id();
        
        $games = Game::with(['boards.user', 'moves.user', 'winner'])
            ->whereHas('boards', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('status', 'finished')
            ->orderByDesc('created_at')
            ->get();

        $wonGames = $games->filter(function($game) use ($userId) {
            return $game->winner_id === $userId;
        })->map(function($game) use ($userId) {
            $opponent = $game->boards->firstWhere('user_id', '!=', $userId);
            return [
                'id' => $game->id,
                'opponent' => $opponent && $opponent->user ? $opponent->user->name : 'Jugador desconocido',
                'created_at' => $game->created_at->format('d/m/Y H:i'),
                'moves_count' => $game->moves->count(),
                'my_board' => [
                    'ships' => $game->boards->firstWhere('user_id', $userId)->ships ?? [],
                    'shots' => $game->boards->firstWhere('user_id', $userId)->shots ?? []
                ],
                'opponent_board' => [
                    'ships' => $opponent->ships ?? [],
                    'shots' => $opponent->shots ?? []
                ],
                'moves' => $game->moves->sortBy('created_at')->map(function($move) {
                    return [
                        'id' => $move->id,
                        'position' => $move->position,
                        'hit' => $move->hit,
                        'shooter' => $move->user->name,
                        'created_at' => $move->created_at->format('H:i:s')
                    ];
                })->values()
            ];
        })->values();

        $lostGames = $games->filter(function($game) use ($userId) {
            return $game->winner_id !== $userId;
        })->map(function($game) use ($userId) {
            $opponent = $game->boards->firstWhere('user_id', '!=', $userId);
            return [
                'id' => $game->id,
                'opponent' => $opponent && $opponent->user ? $opponent->user->name : 'Jugador desconocido',
                'created_at' => $game->created_at->format('d/m/Y H:i'),
                'moves_count' => $game->moves->count(),
                'my_board' => [
                    'ships' => $game->boards->firstWhere('user_id', $userId)->ships ?? [],
                    'shots' => $game->boards->firstWhere('user_id', $userId)->shots ?? []
                ],
                'opponent_board' => [
                    'ships' => $opponent->ships ?? [],
                    'shots' => $opponent->shots ?? []
                ],
                'moves' => $game->moves->sortBy('created_at')->map(function($move) {
                    return [
                        'id' => $move->id,
                        'position' => $move->position,
                        'hit' => $move->hit,
                        'shooter' => $move->user->name,
                        'created_at' => $move->created_at->format('H:i:s')
                    ];
                })->values()
            ];
        })->values();

        return Inertia::render('UserStats', [
            'won' => $wonGames->count(),
            'lost' => $lostGames->count(),
            'total' => $games->count(),
            'wonGames' => $wonGames,
            'lostGames' => $lostGames,
        ]);
    }

    public function gameHistory($gameId)
    {
        $userId = Auth::id();
        $game = Game::with(['boards.user', 'moves'])->where('id', $gameId)
            ->whereHas('boards', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->first();
        if (!$game) {
            abort(403, 'No tienes permiso para ver el historial de esta partida');
        }
        return inertia('GameHistoryView', [
            'game' => $game
        ]);
    }
}
