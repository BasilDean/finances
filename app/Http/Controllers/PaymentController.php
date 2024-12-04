<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Payment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PaymentController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Payment::class);

        return Payment::all();
    }

    public function store(PaymentRequest $request)
    {
        $this->authorize('create', Payment::class);

        return Payment::create($request->validated());
    }

    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);

        return $payment;
    }

    public function update(PaymentRequest $request, Payment $payment)
    {
        $this->authorize('update', $payment);

        $payment->update($request->validated());

        return $payment;
    }

    public function destroy(Payment $payment)
    {
        $this->authorize('delete', $payment);

        $payment->delete();

        return response()->json();
    }
}
