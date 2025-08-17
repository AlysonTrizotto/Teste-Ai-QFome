<?php

namespace App\Http\Controllers\API\User\Authenticable;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Authenticable\AuthenticationRequest;
use App\Services\User\Authenticable\AuthenticateService;

class AuthenticateController extends Controller
{
    public function __construct(private AuthenticateService $authenticateService) {}

    public function authenticate(AuthenticationRequest $request)
    {
        return $this->authenticateService->authenticate($request);
    }
}
