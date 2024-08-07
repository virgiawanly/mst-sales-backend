<?php

namespace App\Http\Controllers\Mobile\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Exception;
use Illuminate\Validation\UnauthorizedException;

class UserController extends Controller
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
     * Get current logged in user profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserProfile()
    {
        try {
            $result = $this->authService->getUserProfile();
            return ResponseHelper::data($result);
        } catch (UnauthorizedException $e) {
            return ResponseHelper::unauthorized($e->getMessage(), $e);
        } catch (Exception $e) {
            return ResponseHelper::internalServerError($e->getMessage(), $e);
        }
    }
}
