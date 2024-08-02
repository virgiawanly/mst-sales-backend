<?php

namespace App\Http\Controllers\WebApp\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\WebApp\Auth\LoginRequest;
use App\Services\AuthService;
use Exception;
use Illuminate\Validation\UnauthorizedException;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\AuthService  $authService
     * @return void
     */
    public function __construct(protected AuthService $authService)
    {
    }

    /**
     * Login web app by issuing token.
     *
     * @param  \App\Http\Requests\WebApp\Auth\LoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        try {
            $result = $this->authService->loginWebApp($request->validated());
            return ResponseHelper::data($result);
        } catch (UnauthorizedException $e) {
            return ResponseHelper::unauthorized($e->getMessage(), $e);
        } catch (Exception $e) {
            return ResponseHelper::internalServerError($e->getMessage(), $e);
        }
    }
}
