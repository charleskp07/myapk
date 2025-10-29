<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\AuthInterface;
use App\Interfaces\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    private UserInterface $userInterface;
    private AuthInterface $authInterface;

    public function __construct(
        UserInterface $userInterface,
        AuthInterface $authInterface,
    ) {
        $this->userInterface = $userInterface;
        $this->authInterface = $authInterface;
    }

    public function show()
    {
        return view('profile.show', [
            'page' => 'profile',
        ]);
    }

    public function edit()
    {
        return view('profile.edit', [
            'page' => 'profile',
        ]);
    }


    public function update(UpdateUserRequest $request)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        $file = $request->file('profile_picture');
        if ($file) {
            $path = $file->store('users', 'public');
            $data['profile_picture'] = $path;
        }

        try {
            if (isset($request->password) || isset($request->actual_password)) {
                if (Auth::attempt([
                    'email' => $data['email'],
                    'password' => $request->actual_password,
                ])) {

                    if (isset($request->password) && isset($request->actual_password))
                        $data['password'] = $request->password;
                } else {
                    return back()->withErrors([
                        'error' => 'Le mot de passe actuel est incorrect !'
                    ])->withInput();
                }
            }

            $this->userInterface->update($data, Auth::id());

            return back()->with('success', "Le profil a été mis à jour a avec succès !");
        } catch (\Exception $ex) {
            // return $ex;
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors du traitement, Réessayez !'
            ])->withInput();
        }
    }


    public function deleteProfilePicture()
    {
        try {
            $user = Auth::user();
            $userData = $user->data;

            if ($userData && $userData->profile_picture) {
                $path = Storage::disk('public')->path($userData->profile_picture);

                if (file_exists($path)) {
                    unlink($path);
                }

                $userData->delete();

                return back()->with('success', 'Photo de profil supprimée avec succès.');
            }

            return back()->withErrors(['error' => 'Aucune photo de profil à supprimer.']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la suppression. Réessayez !']);
        }
    }
}
