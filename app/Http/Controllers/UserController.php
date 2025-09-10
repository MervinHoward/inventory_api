<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $fields = ['id', 'name', 'email'];
        $users = $this->userService->getAll($fields);
        return response()->json(UserResource::collection($users));
    }

    public function show(int $id)
    {
        try {
            $fields = ['id', 'name', 'email'];
            $user = $this->userService->getById($id, $fields);
            return response()->json(new UserResource($user));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }
    }

    public function store(UserRequest $request)
    {
        return $this->userService->create($request->validated());
    }

    public function update(int $id, UserRequest $request)
    {
        try {
            $user = $this->userService->update($id, $request->validated());
            return response()->json(new UserResource($user));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }
    }

    public function delete(int $id)
    {
        try {
            $this->userService->delete($id);
            return response()->json([
                'message' => 'User deleted successfully.'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }
    }
}
