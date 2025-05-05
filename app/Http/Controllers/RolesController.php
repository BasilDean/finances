<?php

namespace App\Http\Controllers;

use App\Http\Resources\RolesResource;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RolesController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return RolesResource::collection(Role::all());
    }

    public function store(Request $request): RolesResource
    {
        $data = $request->validate([

        ]);

        return new RolesResource(Role::create($data));
    }

    public function show(Role $roles): RolesResource
    {
        return new RolesResource($roles);
    }

    public function update(Request $request, Role $roles): RolesResource
    {
        $data = $request->validate([

        ]);

        $roles->update($data);

        return new RolesResource($roles);
    }

    public function destroy(Role $roles): JsonResponse
    {
        $roles->delete();

        return response()->json();
    }
}
