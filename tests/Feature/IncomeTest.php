<?php

use App\Http\Requests\IncomeRequest;
use App\Models\Account;
use App\Models\Budget;
use App\Models\Income;
use App\Models\Setting;
use App\Models\User;
use App\Services\UserSettingsService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->budget = Budget::factory()->create();
    $this->account = Account::factory()->create();
    $this->budget->accounts()->attach($this->account);
    $this->user = User::factory()->create();
    $this->income = Income::factory()->create(['account_id' => $this->account->id, 'user_id' => $this->user->id]);
    (new UserSettingsService())->setActiveBudget($this->user, $this->budget->slug);
    $setting = Setting::factory()->create(['user_id' => $this->user->id, 'active_budget' => $this->budget->slug]);
    $this->user->settings()->save($setting);
});

describe('Income Model', function () {
    test('has factory', closure: function () {
        $this->assertNotNull($this->income);
    });

    it('has the correct fillable attributes', function () {
        expect($this->income->getFillable())->toMatchArray([
            'title',
            'account_id',
            'normalized_title',
            'source',
            'amount',
            'currency',
            'created_at',
            'date',
        ]);
    });

    it('casts the date fields correctly', function () {
        expect($this->income->getCasts())->toMatchArray([
            'date' => 'datetime:Y-m-d H:i:s',
        ]);
    });

    it('uses soft deletes', function () {

        expect(method_exists($this->income, 'bootSoftDeletes'))->toBeTrue();
    });

    it('belongs to a user', function () {

        expect($this->income->user())->toBeInstanceOf(BelongsTo::class);
    });

    it('belongs to an account', function () {
        expect($this->income->account())->toBeInstanceOf(BelongsTo::class);
    });

    it('successfully applies soft deletes', function () {
        $this->income->delete();

        expect(Income::find($this->income->id))->toBeNull()
            ->and(Income::withTrashed()->find($this->income->id))->not->toBeNull();
    });

});

describe('Income Request', function () {

    it('validates all required parameters', function () {
        $validData = [
            'title' => 'Income title',
            'account' => '1',
            'user' => '1',
            'source' => 'Salary',
            'amount' => 100.50,
            'created_at' => '2023-11-01',
            'date' => '2023-11-01',
        ];

        // Validate with all fields present
        $validator = Validator::make($validData, (new IncomeRequest())->rules());
        expect($validator->fails())->toBeFalse(); // No validation errors
    });

    it('fails when required parameters are missing', function () {
        // Test each required field by removing one at a time
        $requiredFields = ['title', 'account', 'user', 'source', 'amount', 'date'];

        foreach ($requiredFields as $missingField) {
            // Valid data with one field missing
            $invalidData = [
                'title' => 'Income title',
                'account' => '1',
                'user' => '1',
                'source' => 'Salary',
                'amount' => 100.50,
                'created_at' => '2023-11-01',
                'date' => '2023-11-01',
            ];
            unset($invalidData[$missingField]); // Remove the field

            // Validate the data
            $validator = Validator::make($invalidData, (new IncomeRequest())->rules());

            expect($validator->fails())->toBeTrue()
                ->and($validator->errors()->has($missingField))->toBeTrue()
                ->and($validator->errors()->first($missingField))
                ->toBe("The $missingField field is required."); // Validation should fail
        }
    });

    it('authorizes the user to create an income', function () {

        $request = new IncomeRequest();
        expect($request->authorize())->toBeFalse();
        $this->get(route('income.create'))->assertStatus(302);

        $this->actingAs($this->user)->get(route('income.index'))->assertStatus(200);
        expect($request->authorize())->toBeFalse();

    });

});

describe('Income Controller index', function () {
    beforeEach(function () {
        Income::factory()->count(20)->create(['user_id' => $this->user->id, 'account_id' => $this->account->id]);
        Income::factory()->create(['title' => 'Test Income', 'amount' => 200, 'currency' => 'USD', 'user_id' => $this->user->id, 'account_id' => $this->account->id]);

        $this->actingAs($this->user); // Authenticate the test user
    });

    it('can list all incomes', function () {
        $response = $this->get(route('income.index'));

        $response->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Incomes/Index')
            ->has('incomes')
        );
    });
    it('filters incomes based on search query', function () {
        $response = $this->get(route('income.index', ['search' => 'test']));
        $response->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Incomes/Index')
            ->has('incomes.data', 1)
            ->where('filters', request()->all('search'))
        );
    });
});

describe('Income Controller create', function () {
    beforeEach(function () {
        $this->actingAs($this->user);
    });
    it('can show form to create Income', function () {
        $response = $this->get(route('income.create'));
        $response->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Incomes/Create')
            ->has('fields')
        );
    });

    it('has all required fields', function () {
        $requiredFields = ['title', 'account', 'user', 'source', 'amount', 'date'];
        $response = $this->get(route('income.create'));
        $response->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Incomes/Create')
            ->has('fields')
            ->where('fields', function ($fields) use ($requiredFields) {
                foreach ($requiredFields as $requiredField) {
                    if (!$fields->has($requiredField)) {
                        return false; // Field is missing in the collection
                    }
                }
                return true; // All fields are present
            })
        );
    });
});

describe('Income Controller store', function () {
    beforeEach(function () {
        $this->actingAs($this->user);
    });
});
