<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrUpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use JWTAuth;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function store(StoreOrUpdateUserRequest $request)
    {
        $user = $this->userService->register($request->all());

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'access_token' => $token
        ]);
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
