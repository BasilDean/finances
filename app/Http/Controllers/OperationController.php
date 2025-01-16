<?php

namespace App\Http\Controllers;

use App\Http\Requests\OperationRequest;
use App\Models\Operation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class OperationController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Operation::class);

        return Operation::all();
    }

    public function store(OperationRequest $request): Operation
    {
        $this->authorize('create', Operation::class);

        return Operation::create($request->validated());
    }

    public function show(Operation $operation): Operation
    {
        $this->authorize('view', $operation);

        return $operation;
    }

    public function update(OperationRequest $request, Operation $operation): Operation
    {
        $this->authorize('update', $operation);

        $operation->update($request->validated());

        return $operation;
    }

    public function destroy(Operation $operation): JsonResponse
    {
        $this->authorize('delete', $operation);

        $operation->delete();

        return response()->json();
    }
}
