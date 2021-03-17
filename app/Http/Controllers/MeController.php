<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\MeService;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        return new UserResource(auth()->user());
    }

    public function update(UpdateUserRequest $request)
    {
        $inputs = $request->validated();
        $user = (new MeService())->update(auth()->user(), $inputs);

        return new UserResource($user);
    }
}
