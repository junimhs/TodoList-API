<?php


namespace App\Services;


use App\Events\ForgotPasswordEvent;
use App\Events\UserRegistered;
use App\Exceptions\ForgotPasswordExistsException;
use App\Exceptions\LoginInvalidException;
use App\Exceptions\TokenResetPasswordExpiredException;
use App\Exceptions\TokenResetPasswordInvalidException;
use App\Exceptions\UserHasBeenTakenException;
use App\Exceptions\VerifyEmailTokenException;
use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
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

        $user->confirmation_token = null;
        $user->email_verified_at = now();

        $user->save();

        return $user;
    }

    /**
     * @param string $email
     * @return string
     * @throws ForgotPasswordExistsException
     */
    public function forgot_password(string $email): string
    {
        $user = User::where('email', $email)->firstOrFail();

        $passwordExist = PasswordReset::where('email', $email)->where('expires_in', '>=', Carbon::now()->toDateString())->first();

        if($passwordExist) {
            throw new ForgotPasswordExistsException();
        }

        $token = Str::random(60);

        PasswordReset::create([
            'email' => $user->email,
            'token' => $token,
            'expires_in' => Carbon::now()->addHour(1)
        ]);

        event(new ForgotPasswordEvent($user, $token));

        return '';
    }

    /**
     * @param string $token
     * @param string $password
     * @return string
     * @throws TokenResetPasswordExpiredException
     * @throws TokenResetPasswordInvalidException
     */
    public function reset_password(string $token, string $password): string
    {
        $passwordToken = PasswordReset::where('token', $token)->first();

        if(!$passwordToken) {
            throw new TokenResetPasswordInvalidException();
        }

        $passwordExpired = PasswordReset::where('token', $token)->where('expires_in', '>=', Carbon::now()->toDateString())->first();

        if(!$passwordExpired) {
            PasswordReset::where('token', $token)->delete();
            throw new TokenResetPasswordExpiredException();
        }

        User::where('email', $passwordExpired->email)->update([
           'password' => bcrypt($password)
        ]);

        return '';
    }
}
