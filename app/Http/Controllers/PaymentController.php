<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Budget;
use App\Models\Payment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): \Inertia\Response
    {
        $this->authorize('viewAny', Payment::class);
        $search = $request->input('search');

        $budget = Budget::where('slug', auth()->user()->settings['active_budget'])->firstOrFail();

        $query = $budget->payments()->orderBy('updated_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('normalized_title', 'like', '%' . $search . '%')
                    ->orWhere('amount', 'like', '%' . $search . '%')
                    ->orWhere('currency', 'like', '%' . $search . '%');
            });
        }
        $items = $query->paginate(10);
        $fields = Payment::getFields();
        $filters = [...request()->all('search')];

        return Inertia::render('Payments/Index', [
            'items' => $items,
            'fields' => $fields,
            'filters' => $filters,
        ]);
    }

    public function store(PaymentRequest $request): Payment
    {
        $this->authorize('create', Payment::class);

        return Payment::create($request->validated());
    }

    public function create(): \Inertia\Response
    {
        $this->authorize('create', Payment::class);
        $fields = Payment::getFields();
        return Inertia::render('Payments/Create', [
            'item' => null,
            'fields' => $fields
        ]);
    }

    public function show(Payment $payment): Payment
    {
        $this->authorize('view', $payment);

        return $payment;
    }

    public function update(PaymentRequest $request, Payment $payment): Payment
    {
        $this->authorize('update', $payment);

        $payment->update($request->validated());

        return $payment;
    }

    public function destroy(Payment $payment): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('delete', $payment);

        $payment->delete();

        return redirect()->route('payments.index')->with('status', 'Payment deleted.');
    }
}
