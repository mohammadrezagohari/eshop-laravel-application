<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as HTTPResponse;

class RegisterController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        try {
            return response()->json(
                $this->authService->register($request->all()),
                HTTPResponse::HTTP_OK
            );
        } catch (ValidationException $exception) {
            return response()->json([
                'validationError' => $exception->errors()
            ], HTTPResponse::HTTP_BAD_REQUEST);
        }
    }

    public function login(Request $request)
    {
        try {
            return response()->json(
                $this->authService->login($request->all()),
                HTTPResponse::HTTP_OK
            );
        } catch (ValidationException $exception) {
            return response()->json([
                'validationError' => $exception->errors()
            ], HTTPResponse::HTTP_BAD_REQUEST);
        }
    }

    public function logout(Request $request)
    {
        if (!$request->user()) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], HTTPResponse::HTTP_UNAUTHORIZED);
        }

        $this->authService->logout($request->user());

        return response()->json([
            'message' => 'successfully logged out',
        ], HTTPResponse::HTTP_OK);
    }
}
