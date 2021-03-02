<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ForgotPasswordExistsException;
use App\Exceptions\LoginInvalidException;
use App\Exceptions\TokenResetPasswordExpiredException;
use App\Exceptions\TokenResetPasswordInvalidException;
use App\Exceptions\VerifyEmailTokenException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthForgotPasswordRequest;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Http\Requests\Auth\AuthResetPasswordRequest;
use App\Http\Requests\Auth\AuthVerifyEmailRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param AuthLoginRequest $request
     * @return UserResource
     * @throws LoginInvalidException
     */
    public function login(AuthLoginRequest $request): UserResource
    {
        $input = $request->validated();

        $token = $this->authService->login($input['email'], $input['password']);

        return (new UserResource(auth()->user()))->additional($token);
    }

    public function register(AuthRegisterRequest $request)
    {
        $input = $request->validated();
        $user = $this->authService->register($input['first_name'], $input['last_name'] ?? '', $input['email'], $input['password']);

        return new UserResource($user);
    }

    /**
     * @param AuthVerifyEmailRequest $request
     * @return UserResource
     * @throws VerifyEmailTokenException
     */
    public function verify_email(AuthVerifyEmailRequest $request): UserResource
    {
        $input = $request->validated();
        $user = $this->authService->verify_email($input['token']);

        return new UserResource($user);
    }

    /**
     * @param AuthForgotPasswordRequest $request
     * @return string
     * @throws ForgotPasswordExistsException
     */
    public function forgot_password(AuthForgotPasswordRequest $request): string
    {
        $input = $request->validated();
        return $this->authService->forgot_password($input['email']);
    }

    /**
     * @param AuthResetPasswordRequest $request
     * @return string
     * @throws TokenResetPasswordExpiredException
     * @throws TokenResetPasswordInvalidException
     */
    public function reset_password(AuthResetPasswordRequest $request): string
    {
        $input = $request->validated();
        return $this->authService->reset_password($input['token'], $input['password']);
    }
}
