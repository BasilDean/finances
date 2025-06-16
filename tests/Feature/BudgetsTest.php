<?php


use App\Models\Account;
use App\Models\Budget;
use App\Models\User;

test('use soft deletes', function () {
    $budget = Budget::factory()->create();
    $budget->delete();
    $this->assertSoftDeleted($budget);
});

test('can have multiple users', function () {
    $budget = Budget::factory()->create();
    $user = User::factory()->create();
    $user2 = User::factory()->create();
    $budget->users()->attach($user);
    $budget->users()->attach($user2);
    $budget->save();
    $this->assertContains($user->id, $budget->users->pluck('id'));
    $this->assertContains($user2->id, $budget->users->pluck('id'));
    $this->assertCount(2, $budget->users);
});

test('can have multiple accounts', function () {
    $budget = Budget::factory()->create();
    $account = Account::factory()->create();
    $account2 = Account::factory()->create();
    $budget->accounts()->attach($account);
    $budget->accounts()->attach($account2);
    $budget->save();
    $this->assertContains($account->id, $budget->accounts->pluck('id'));
    $this->assertContains($account2->id, $budget->accounts->pluck('id'));
    $this->assertCount(2, $budget->accounts);
});



//test('can have multiple payments', function () {
//    $user = User::factory()->create();
//    $budget = Budget::factory()->create();
//    (new UserSettingsService())->setActiveBudget($user, $budget);
//    var_dump($user);
//    $payment = Payment::factory()->create();
//    $payment2 = Payment::factory()->create();
//    $payment->budget_id = $budget->id;
//    $payment2->budget_id = $budget->id;
//    $payment->save();
//    $payment2->save();
//    $this->assertContains($payment->id, $budget->payments->pluck('id'));
//    $this->assertContains($payment2->id, $budget->payments->pluck('id'));
//    $this->assertCount(2, $budget->payments);
//});

//test('can have history', function () {
// TODO create test
//});
