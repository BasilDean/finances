<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Services\SearchService;
use App\Services\UserSettingsService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    use AuthorizesRequests;

    private SearchService $searchService;
    private UserSettingsService $userSettingsService;

    public function __construct(UserSettingsService $userSettingsService)
    {
        $this->userSettingsService = $userSettingsService;
    }

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Payment::class);
        $search = $request->input('search');

        $budget = $this->userSettingsService->getActiveBudget();

        /** @noinspection NullPointerExceptionInspection */
        $query = $budget->payments()->with('user')->orderBy('updated_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('normalized_title', 'like', '%' . $search . '%')
                    ->orWhere('amount', 'like', '%' . $search . '%')
                    ->orWhere('currency', 'like', '%' . $search . '%');
            });
        }
        $items = $query->paginate(10);
        $items->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'slug' => $item->slug,
                'amount' => $item->amount,
                'currency' => $item->currency,
                'regular' => (bool)$item->regular,
                'created_at' => $item->created_at->format('H:i d-m-Y'),
                'date' => $item->date,
                'user' => $item->user->name ?? null, // Extract user's name
                'total_paid' => $item->total_paid,
                'total_due' => $item->total_due,
            ];
        });
        $fields = PaymentResource::getFields('show');
        $filters = [...request()->all('search')];

        return Inertia::render('Payments/Index', [
            'items' => $items,
            'fields' => $fields,
            'filters' => $filters,
        ]);
    }

    public function store(PaymentRequest $request): RedirectResponse
    {
        $this->authorize('create', Payment::class);
        $payment = new Payment($request->validated());

        $date = $request->date;
        $formattedDate = Carbon::parse($date)->format('Y-m-d H:i:s');
        $payment->date = $formattedDate;
        $payment->user_id = $request->user['id'];
        $payment->save();
        return redirect()->route('payments.index')->with('status', 'Payment created.');
    }

    public function create(): Response
    {
        $this->authorize('create', Payment::class);
        $fields = PaymentResource::getFields('edit');
        return Inertia::render('Payments/Create', [
            'item' => null,
            'fields' => $fields,
            'type' => 'payments'
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

    public function destroy(Payment $payment): RedirectResponse
    {
        $this->authorize('delete', $payment);

        $payment->delete();

        return redirect()->route('payments.index')->with('status', 'Payment deleted.');
    }
}
