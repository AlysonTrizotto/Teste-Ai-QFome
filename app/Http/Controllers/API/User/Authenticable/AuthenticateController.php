<?php

namespace App\Http\Controllers\API\User\Authenticable;

use App\Enum\HttpCodeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Authenticable\AuthenticationRequest;
use App\Services\User\Authenticable\AuthenticateService;

class AuthenticateController extends Controller
{
    public function __construct(private AuthenticateService $authenticateService) {}

    public function authenticate(AuthenticationRequest $request)
    {
        try {
            return $this->sendSuccessResponse($this->authenticateService->authenticate($request), 'Authentication successful', HttpCodeEnum::OK->value);
        } catch (\Exception $e) {
            return $this->sendFailResponse($e);
        }
    }
}
