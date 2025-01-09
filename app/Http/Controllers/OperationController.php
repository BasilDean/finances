<?php

namespace App\Http\Controllers;

use App\Http\Requests\OperationRequest;
use App\Models\Operation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OperationController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Operation::class);

        return Operation::all();
    }

    public function store(OperationRequest $request)
    {
        $this->authorize('create', Operation::class);

        return Operation::create($request->validated());
    }

    public function show(Operation $operation)
    {
        $this->authorize('view', $operation);

        return $operation;
    }

    public function update(OperationRequest $request, Operation $operation)
    {
        $this->authorize('update', $operation);

        $operation->update($request->validated());

        return $operation;
    }

    public function destroy(Operation $operation)
    {
        $this->authorize('delete', $operation);

        $operation->delete();

        return response()->json();
    }
}
