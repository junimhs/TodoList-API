<?php


namespace App\Services;


use App\Models\User;

class MeService
{
    public function update(User $user, array &$input)
    {
        if(!empty($input['password'])) {
            $input['password'] = bcrypt($input['password']);
        }

        $user->fill(array_filter($input));
        $user->save();

        return $user->fresh();
    }
}
