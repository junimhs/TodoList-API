<?php


namespace App\Services;


use App\Events\UserRegistered;
use App\Exceptions\LoginInvalidException;
use App\Exceptions\UserHasBeenTakenException;
use App\Exceptions\VerifyEmailTokenException;
use App\Models\User;
use Illuminate\Support\Str;

class AuthService
{
    /**
     * @param string $email
     * @param string $password
     * @return array
     * @throws LoginInvalidException
     */
    public function login(string $email, string $password): array
    {
        $login = [
            'email' => $email,
            'password' => $password
        ];

        if (!$token = auth()->attempt($login)) {
            throw new LoginInvalidException();
        }

        return [
            'access_token' => $token,
            'token_type' => 'Bearer'
        ];
    }

    public function register(string $firstName, string $lastName, string $email, string $password)
    {
        $userExists = User::where('email', $email)->exists();

        if(!empty($userExists)) {
            throw new UserHasBeenTakenException();
        }

        $userPassword = bcrypt($password || Str::random(10));
        $confirmationToken = Str::random(60);

        $user = User::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => $userPassword,
            'confirmation_token' => $confirmationToken
        ]);

        event(new UserRegistered($user));

        return $user;
    }

    public function verify_email(string $token)
    {
        $user = User::where('confirmation_token', $token)->first();

        if(empty($user)) {
            throw new VerifyEmailTokenException();
        }

        $user->confirmation_token = '';
        $user->email_verified_at = now();

        $user->save();

        return $user;
    }
}
