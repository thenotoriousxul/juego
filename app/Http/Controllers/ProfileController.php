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

    // Página de estadísticas del usuario
    public function stats()
    {
        $userId = Auth::id();
        $games = Game::with(['boards.user', 'moves'])
            ->whereHas('boards', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->where('status', 'finished')
            ->orderByDesc('created_at')
            ->get();
        $won = $games->where('winner_id', $userId)->values();
        $lost = $games->where('winner_id', '!=', $userId)->values();
        return inertia('UserStats', [
            'won' => $won->count(),
            'lost' => $lost->count(),
            'total' => $games->count(),
            'wonGames' => $won,
            'lostGames' => $lost,
        ]);
    }

    // Detalle de historial de una partida
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
