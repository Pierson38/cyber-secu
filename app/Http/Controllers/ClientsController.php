<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClientRequest;
use App\Models\Client;
use App\Models\RoleEnum;
use App\Models\User;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    public function list(Request $request)
    {
        /** @var $connectedUser User|null */
        $connectedUser = session()->get(AuthController::USER_SESSION_KEY);
        if (!$connectedUser->isUserApproved()) {
            return redirect('dashboard')->with('connexion.error', "Vous devez être approuvé !");
        }

        $query = $request->get('q', '');

        if ($query !== null && $query !== "") {
            $clients = Client::whereAssigneeId($connectedUser->id)
                ->where('name', 'LIKE', sprintf('%s%s%s', "%", $query, "%"))
                ->orWhere('address', 'LIKE', sprintf('%s%s%s', "%", $query, "%"))
                ->orWhere('contact_info', 'LIKE', sprintf('%s%s%s', "%", $query, "%"))
                ->get();
        } else {
            $clients = Client::whereAssigneeId($connectedUser->id)->get();
        }

        return view('clients.list', [
            'orphans_clients' => Client::whereAssigneeId(null)->get(),
            'user_clients' => $clients,
            'user' => $connectedUser,
        ]);
    }

    public function auto_assignee($clientId)
    {
        $clientToAssign = Client::whereId($clientId)->first();

        if ($clientToAssign === null || $clientToAssign->assignee_id !== null) {
            return redirect()->back()->with('connexion.error', 'Le client est inexistant ou a déjà un référent !');
        }

        /** @var $connectedUser User|null */
        $connectedUser = session()->get(AuthController::USER_SESSION_KEY);
        $clientToAssign->assignee_id = $connectedUser->id;
        $clientToAssign->save();
        return redirect()->back()->with('connexion.success', 'Le client vous est à présent attribué.');
    }

    public function create_client(CreateClientRequest $request)
    {
        /** @var $connectedUser User|null */
        $connectedUser = session()->get(AuthController::USER_SESSION_KEY);

        $assignee_id = $request->validated('assignee_id') ?? $connectedUser->id;
        if ($connectedUser->role !== RoleEnum::ADMINISTRATEUR->name) {
            $assignee_id = $connectedUser->id; // Only administrateur can assign at creation
        } else {
            // We check for existence of course
            if (!User::whereId($assignee_id)->exists()) {
                return redirect()->back()->with('connexion.error', "L'utilisateur référent n'existe pas !");
            }
        }

        Client::create(
            array_merge($request->validated(), [
                'assignee_id' => $assignee_id
            ])
        );

        return redirect()->back()->with('connexion.success', 'Client créé et attribué !');
    }
}
