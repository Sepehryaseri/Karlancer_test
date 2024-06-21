<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Services\UserService;
use App\Traits\ApiResponder;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    use ApiResponder;

    public function __construct(private readonly UserService $userService)
    {
    }

    public function register(RegisterUserRequest $request)
    {
        $data = $request->validated();
        $response = $this->userService->register($data);
        if ($response['status'] != Response::HTTP_CREATED) {
            return $this->failed($response['message'], $response['status']);
        }
        return $this->success($response['data'], $response['message'], $response['status']);
    }

    public function login(LoginUserRequest $request)
    {
        $data = $request->validated();
        $response = $this->userService->login($data);
        if ($response['status'] != Response::HTTP_OK) {
            return $this->failed($response['status'], $response['message']);
        }
        return $this->success($response['data']);
    }

    public function logout()
    {
        $response = $this->userService->logout();
        if ($response['status'] != Response::HTTP_OK) {
            return $this->failed($response['status'], $response['message']);
        }
        return $this->success(message: $response['message']);
    }
}
