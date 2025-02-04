<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExchangeRequest;
use App\Http\Resources\ExchangeResource;
use App\Models\Exchange;
use App\Models\User;
use App\Services\CurrencyLayerService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ExchangeController extends Controller
{
    use AuthorizesRequests;

    protected CurrencyLayerService $currencyLayerService;

    public function __construct(CurrencyLayerService $currencyLayerService)
    {
        $this->currencyLayerService = $currencyLayerService;
    }


    public function index(): Response
    {
        $this->authorize('viewAny', Exchange::class);


        $exchanges = Exchange::with(['accountFrom', 'accountTo'])->orderBy('date', 'desc')->paginate(10);
        Carbon::setLocale('ru');
        $exchanges->getCollection()->transform(function ($exchange) {

            $user = User::find($exchange->user_id);
            return [
                'id' => $exchange->id,
                'slug' => $exchange->slug,
                'amount_from' => $exchange->amount_from,
                'title' => $exchange->amount_from,
                'account_from' => $exchange->accountFrom->title,
                'currency_from' => $exchange->currency_from,
                'amount_to' => $exchange->amount_to,
                'account_to' => $exchange->accountTo->title,
                'currency_to' => $exchange->currency_to,
                'exchange_rate' => $exchange->exchange_rate,
                'oficial_rate' => $exchange->oficial_rate,
                'user' => $user->name,
                'date' => $exchange->date->translatedFormat('H:i, l, (d M Y)')
            ];
        });
        $fields = ExchangeResource::getFields('show');
        return Inertia::render('Exchange/Index', [
            'exchanges' => $exchanges,
            'fields' => $fields,
        ]);
    }

    public function store(ExchangeRequest $request): RedirectResponse
    {
        $this->authorize('create', Exchange::class);
        $currencyFrom = $request->account_from['currency'];
        $currencyTo = $request->account_to['currency'];
        $idFrom = $request->account_from['id'];
        $idTo = $request->account_to['id'];
        $userId = $request->user['id'];
        $date = $request->date;
        $formattedDate = Carbon::parse($date)->format('Y-m-d H:i:s');
        $data = [
            'amount_from' => $request->amount_from,
            'account_from' => $idFrom,
            'currency_from' => $currencyFrom,
            'amount_to' => $request->amount_to,
            'account_to' => $idTo,
            'currency_to' => $currencyTo,
            'exchange_rate' => $request->amount_from / $request->amount_to,
            'oficial_rate' => 0,
            'user_id' => $userId,
            'date' => $formattedDate,
        ];

        $exchange = Exchange::create($data);
        $exchange->accountFrom()->associate($request->account_from['id']);
        $exchange->accountTo()->associate($request->account_to['id']);

        if (!app()->environment('local')) {
            $rates = $this->currencyLayerService->getRates(
                $currencyTo,
                $currencyFrom
            );

            // Extract the desired rate
            $rateKey = "{$currencyTo}{$currencyFrom}";
            $exchange->oficial_rate = $rates['quotes'][$rateKey] ?? 0;
        }
        $exchange->save();

        return redirect()->route('exchanges.index')->with('success', 'Exchange created successfully');
    }

    public function create(): Response
    {
        $this->authorize('create', Exchange::class);

        $fields = ExchangeResource::getFields('edit');
        return Inertia::render('Exchange/Create', [
            'fields' => $fields,
        ]);
    }

    public function show(Exchange $exchange): Exchange
    {
        $this->authorize('view', $exchange);

        return $exchange;
    }

    public function edit(Exchange $exchange): Response
    {
        $this->authorize('update', $exchange);

        $fields = ExchangeResource::getFields('edit');

        $user = User::find($exchange->user_id);

        $data = [
            'id' => $exchange->id,
            'slug' => $exchange->slug,
            'amount_from' => $exchange->amount_from,
            'title' => 'перевод',
            'account_from' => $exchange->accountFrom,
            'currency_from' => $exchange->currency_from,
            'amount_to' => $exchange->amount_to,
            'account_to' => $exchange->accountTo,
            'currency_to' => $exchange->currency_to,
            'exchange_rate' => $exchange->exchange_rate,
            'oficial_rate' => $exchange->oficial_rate,
            'user' => $user,
            'date' => $exchange->date->format('H:i d-m-Y')
        ];

        return Inertia::render('Exchange/Edit', [
            'item' => $data,
            'fields' => $fields,
        ]);
    }

    public function update(ExchangeRequest $request, Exchange $exchange): RedirectResponse
    {
        $this->authorize('update', $exchange);

        $currencyFrom = $request->account_from['currency'];
        $currencyTo = $request->account_to['currency'];
        $idFrom = $request->account_from['id'];
        $idTo = $request->account_to['id'];
        $userId = $request->user['id'];

        $date = $request->date;
        $formattedDate = Carbon::parse($date)->format('Y-m-d H:i:s');
        $data = [
            'amount_from' => $request->amount_from,
            'account_from' => $idFrom,
            'currency_from' => $currencyFrom,
            'amount_to' => $request->amount_to,
            'account_to' => $idTo,
            'currency_to' => $currencyTo,
            'exchange_rate' => $request->amount_from / $request->amount_to,
            'oficial_rate' => 0,
            'user_id' => $userId,
            'date' => $formattedDate,
        ];
        $exchange->update($data);

        $exchange->accountFrom()->associate($request->account_from['id']);
        $exchange->accountTo()->associate($request->account_to['id']);


        return redirect()->route('exchanges.index')->with('status', 'Exchange updated.');
    }

    public function destroy(Exchange $exchange): RedirectResponse
    {
        $this->authorize('delete', $exchange);

        $exchange->delete();

        return redirect()->route('exchanges.index')->with('status', 'Exchange deleted.');
    }
}
