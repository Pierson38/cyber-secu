<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccessRequestRequest;
use App\Http\Requests\AttemptLoginRequest;
use App\Models\JWTModel;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    const string USER_SESSION_KEY = "user";

    public static function isLoggedIn()
    {
        return session()->get(self::USER_SESSION_KEY, null) !== null;
    }

    public function index()
    {
        return view('login');
    }

    public function logout()
    {
        session()->remove(self::USER_SESSION_KEY);
        return redirect('login')->with('connexion.success', 'Vous vous êtes déconnecté.');
    }


    public function attemptLogin(AttemptLoginRequest $request)
    {
        $user = User::where(
            'email', '=', $request->validated()["email"]
        )->get();

        if ($user->containsOneItem()) {
            if (!$user[0]->checkIfPasswordIsCorrect($request->validated()["password"])) {
                return redirect()->back()->with('connexion.error', 'Mot de passe erroné.');
            }
            $jwtCreator = new JWTModel();

            $jwtCookie = Cookie::make(
                JWTModel::JWT_SESSION_COOKIE_NAME,
                $jwtCreator->getJWT($user[0]->attributesToArray()),
                3600,
                "/",
                null,
                false,
                false // We work with JS
            );
            session()->put(self::USER_SESSION_KEY, $user[0]);

            return redirect('dashboard')->withCookie($jwtCookie)
                ->with('connexion.success', 'Connexion réussie en tant que ' .
                    $user[0]->getTextPresentation());
        } else {
            return redirect()->back()->with('connexion.error', 'Utilisateur non trouvé.');
        }
    }

    public function access_request(AccessRequestRequest $request)
    {
        $new_user = new User($request->validated());
        $new_user->save();

        return redirect('login')->with('connexion.success',
            "Demande de création effectuée, un administrateur validera votre demande sous peu.");
    }

}
