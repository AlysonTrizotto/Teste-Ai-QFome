<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Services\User\UserService;
use App\Http\Requests\User\User\StoreUserRequest;
use App\Http\Requests\User\User\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Http\Resources\User\UserResource;

class UserController extends Controller
{
    public function __construct(private UserService $service) {}

    public function index(Request $request)
    {
        $data = $this->service->paginate($request->all(), (int) $request->get('per_page', 15));
        // Wrap paginator in Resource so it handles pagination metadata automatically
        return new UserResource($data);
    }

    public function store(StoreUserRequest $request)
    {
        $model = $this->service->create($request->validated());
        return (new UserResource($model))->response()->setStatusCode(201);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        $updated = $this->service->update($user, $request->validated());
        return new UserResource($updated);
    }

    public function destroy(User $user)
    {
        $this->service->delete($user);
        return response()->noContent();
    }
}