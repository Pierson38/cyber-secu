<?php

namespace App\Http\Controllers;

use App\Http\Requests\FirstApprovalRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\RoleEnum;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $amount_of_waiters = User::whereApprovalBy(null)->count();
        return view('dashboard', [
            'user' => session()->get(AuthController::USER_SESSION_KEY),
            'waitersCount' => $amount_of_waiters
        ]);
    }

    public function users_list()
    {
        /** @var User $currentUser */
        $currentUser = session()->get(AuthController::USER_SESSION_KEY);
        if (!$currentUser->isUserApproved()) {
            return redirect('dashboard')->with('connexion.error', "Vous devez être approuvé !");
        }
        return view('users.list', [
            'users' => User::orderBy('id', 'ASC')->get(),
        ]);
    }

    public function user_approve(int $userIdToApprove, int $state)
    {
        /** @var User $currentUser */
        $currentUser = session()->get(AuthController::USER_SESSION_KEY);

        // Of course, we don't want to be locked outside the CRM, so we prevent déapproval for us.
        // We are no dumb ...
        // But we have to keep allowing to validate first user created.
        if ($state === 0 && $userIdToApprove === $currentUser->id) {
            return redirect()->back()->with('connexion.error', 'Vous ne pouvez pas vous désactiver !');
        }

        $userToApprove = User::whereId($userIdToApprove)->first();
        if (!$userToApprove) {
            return redirect()->back()->with('connexion.error',
                sprintf("L'utilisateur %s n'existe pas", $userIdToApprove));
        }

        if ($state === 0) {
            $userToApprove->approval_by = null;
            $userToApprove->approval_at = null;
        } else {
            $userToApprove->approval_by = $currentUser->id;
            $userToApprove->approval_at = new \DateTimeImmutable();
        }
        $userToApprove->save();
        return redirect()->back();
    }

    public function user_delete(int $userIdToDelete)
    {
        /** @var User $currentUser */
        $currentUser = session()->get(AuthController::USER_SESSION_KEY);

        if ($userIdToDelete === $currentUser->id) {
            return redirect()->back()->with('connexion.error', 'Vous ne pouvez pas vous supprimer !');
        }

        $userToDelete = User::whereId($userIdToDelete)->first();
        if (!$userToDelete) {
            return redirect()->back()->with('connexion.error',
                sprintf("L'utilisateur %s n'existe pas, impossible de supprimer", $userIdToDelete));
        }

        $userToDelete->delete();
        return redirect()->back();
    }

    public function display_first_approval()
    {
        return view('users.approve_first');
    }

    public function process_first_approval(FirstApprovalRequest $request)
    {
        $amountOfValidatedAdmins = User::where('role', '=', RoleEnum::ADMINISTRATEUR->name)
            ->where('approval_by', '<>', 'NULL')->count();

        if ($amountOfValidatedAdmins > 0) {
            return redirect()->back()->with('connexion.error', "Il existe au moins 1 administrateur approuvé.");
        }

        $foundUser = User::where('email', '=', $request->validated('email'))->first();

        if (!$foundUser) {
            return redirect()->back()->with('connexion.error',
                sprintf("L'utilisateur %s n'existe pas", $request->validated('email')));
        }

        if ($foundUser->role !== RoleEnum::ADMINISTRATEUR->name) {
            return redirect()->back()->with('connexion.error',
                sprintf("L'utilisateur %s n'est pas un administrateur", $request->validated('email')));
        }

        if ($foundUser->approval_by !== null) {
            return redirect()->back()->with('connexion.error',
                sprintf("L'utilisateur %s est déjà approuvé", $request->validated('email')));
        }

        // To mitigate DB request, we check admin password at the end of method
        $creatorUser = User::where('email', '=', "jean-marc.picaule@gmail.com")->first();

        if (!$creatorUser) {
            return redirect()->back()->with('connexion.error', "Administrateur introuvable !!");
        }

        if (!$creatorUser->checkIfPasswordIsCorrect($request->validated('password'))) {
            return redirect()->back()->with('connexion.error', "Mot de passe erroné !");
        }

        /** @var User $currentUser */
        $currentUser = session()->get(AuthController::USER_SESSION_KEY);
        $foundUser->approval_by = $currentUser->id;
        $foundUser->approval_at = new \DateTimeImmutable();

        $foundUser->save();
        return redirect()->back()->with('connexion.success', "Utilisateur approuvé !");
    }

    public function update_user(UpdateUserRequest $request)
    {
        /** @var User $currentUser */
        $currentUser = session()->get(AuthController::USER_SESSION_KEY);

        $currentUser->name = $request->validated('name');

        if ($request->validated('password') !== null) {
            if (!$currentUser->checkIfPasswordIsCorrect($request->validated('password'))) {
                return redirect()->back()->with('connexion.error', "Mot de passe erroné !");
            }

            if ($request->validated('password-new') === null) {
                return redirect()->back()->with('connexion.error', "Nouveau mot de passe invalide");
            }

            if (
                $request->validated('password-new', "") !==
                $request->validated('password-confirm', "")
            ) {
                return redirect()->back()->with('connexion.error', "Les nouveaux mots de passes ne correspondent pas");
            }

            $currentUser->password = $request->validated('password-new');
        }

        if ($request->validated('user-picture') !== null) {
            $currentUser->picture = $request->validated('user-picture');
        }

        $currentUser->save();
        return redirect()->back()->with('connexion.success', "Profil mis à jour !");
    }

    public function get_picture_file(string $path = null)
    {
        if (file_exists($path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($path) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($path));
            readfile($path);
        }
    }
}
