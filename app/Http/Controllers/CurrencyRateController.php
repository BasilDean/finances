<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurrencyRateRequest;
use App\Models\CurrencyRate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class CurrencyRateController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', CurrencyRate::class);

        return CurrencyRate::all();
    }

    public function store(CurrencyRateRequest $request): CurrencyRate
    {
        $this->authorize('create', CurrencyRate::class);

        return CurrencyRate::create($request->validated());
    }

    public function show(CurrencyRate $currencyRate): CurrencyRate
    {
        $this->authorize('view', $currencyRate);

        return $currencyRate;
    }

    public function update(CurrencyRateRequest $request, CurrencyRate $currencyRate): CurrencyRate
    {
        $this->authorize('update', $currencyRate);

        $currencyRate->update($request->validated());

        return $currencyRate;
    }

    public function destroy(CurrencyRate $currencyRate): JsonResponse
    {
        $this->authorize('delete', $currencyRate);

        $currencyRate->delete();

        return response()->json();
    }
}
