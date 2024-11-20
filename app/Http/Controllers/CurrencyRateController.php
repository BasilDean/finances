<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurrencyRateRequest;
use App\Models\CurrencyRate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CurrencyRateController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', CurrencyRate::class);

        return CurrencyRate::all();
    }

    public function store(CurrencyRateRequest $request)
    {
        $this->authorize('create', CurrencyRate::class);

        return CurrencyRate::create($request->validated());
    }

    public function show(CurrencyRate $currencyRate)
    {
        $this->authorize('view', $currencyRate);

        return $currencyRate;
    }

    public function update(CurrencyRateRequest $request, CurrencyRate $currencyRate)
    {
        $this->authorize('update', $currencyRate);

        $currencyRate->update($request->validated());

        return $currencyRate;
    }

    public function destroy(CurrencyRate $currencyRate)
    {
        $this->authorize('delete', $currencyRate);

        $currencyRate->delete();

        return response()->json();
    }
}
