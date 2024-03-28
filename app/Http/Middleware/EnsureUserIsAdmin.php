<?php

namespace App\Http\Middleware;

use App\Http\Controllers\AuthController;
use App\Models\RoleEnum;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var $connectedUser User|null */
        $connectedUser = session()->get(AuthController::USER_SESSION_KEY);

        if (!$connectedUser) {
            return redirect('login')->with('connexion.error',
                "Utilisateur du jeton introuvable, merci de vous connecter à nouveau.");
        }

        if ($connectedUser->role !== RoleEnum::ADMINISTRATEUR->name) {
            return redirect('dashboard')->with('connexion.error',
                "Le role ADMINISTRATEUR est nécessaire pour accéder à cette page.");
        }

        return $next($request);
    }
}
