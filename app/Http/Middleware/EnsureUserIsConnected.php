<?php

namespace App\Http\Middleware;

use App\Http\Controllers\AuthController;
use App\Models\JWTModel;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsConnected
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $jwtCookie = $request->cookie(JWTModel::JWT_SESSION_COOKIE_NAME);
        if (!$jwtCookie) {
            return redirect('login')->with('connexion.error',
                "Jeton d'accès manquant, merci de vous connecter à nouveau.");
        }
        $jwtDecryptor = new JWTModel();
        $decryptedJwt = $jwtDecryptor->parseJWT($jwtCookie);
        if (is_null($decryptedJwt)) {
            return redirect('login')->with('connexion.error',
                "Jeton d'accès erroné, merci de vous connecter à nouveau.");
        }

        // Of course, we test for existence of user, in case of deletion :)
        $data = $decryptedJwt->claims()->get('data');
        if (is_null($data)) {
            return redirect('login')->with('connexion.error',
                "data dans le jeton introuvable, merci de vous connecter à nouveau.");
        }

        $users = User::where('email', '=', $data["email"])
            ->where('role', '=', $data["role"])->get();
        if (!$users->containsOneItem()) {
            return redirect('login')->with('connexion.error',
                "Utilisateur du jeton introuvable, merci de vous connecter à nouveau.");
        }
        session()->put(AuthController::USER_SESSION_KEY, $users[0]);

        return $next($request);
    }
}
