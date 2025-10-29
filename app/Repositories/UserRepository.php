<?php

namespace App\Repositories;

use App\Interfaces\UserInterface;
use App\Models\User;
use App\Models\UserData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserRepository implements UserInterface
{
    public function store(array $data)
    {
        return User::create($data);
    }

    public function show(string $id)
    {
        return User::find($id);
    }

    public function update(array $data, string $id)
    {
        $user = User::find($id);

        if (isset($data['profile_picture']) && !empty($data['profile_picture'])) {

            if ($user->data && $user->data->profile_picture) {
                unlink(Storage::disk('public')->path($user->data->profile_picture));
            }

            $userData = UserData::where('user_id', $id)->first();

            if ($userData)
                $userData->delete();


            UserData::create([
                'user_id' => $id,
                'profile_picture' => $data['profile_picture']
            ]);
        }

        $user->update($data);
    }

    public function destroy(string $id)
    {

        $user = User::find($id);
        if ($id != Auth::id() && $id != 1)
            $user->delete();
    }
}
