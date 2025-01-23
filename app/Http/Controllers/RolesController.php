<?php

namespace App\Http\Controllers;

use App\Http\Resources\RolesResource;
use App\Models\Role;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function index()
    {
        return RolesResource::collection(Role::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([

        ]);

        return new RolesResource(Role::create($data));
    }

    public function show(Role $roles)
    {
        return new RolesResource($roles);
    }

    public function update(Request $request, Role $roles)
    {
        $data = $request->validate([

        ]);

        $roles->update($data);

        return new RolesResource($roles);
    }

    public function destroy(Role $roles)
    {
        $roles->delete();

        return response()->json();
    }
}
