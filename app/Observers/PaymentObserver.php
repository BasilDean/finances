<?php

namespace App\Observers;

use App\Models\Payment;
use App\Services\UserSettingsService;

class PaymentObserver
{
    public function creating(Payment $payment): void
    {
        $payment->title = ucfirst($payment->title);
        $payment->normalized_title = mb_strtolower($payment->title);
        $payment->date = $payment->date ?? $payment->created_at;
        $payment->slug = $this->generateUniqueSlug();
        $budget = (new UserSettingsService())->getActiveBudget();
        /** @noinspection NullPointerExceptionInspection */
        $payment->budget_id = $budget->id;
        $payment->total = $payment->total ?? 0;
        $payment->total_paid = $payment->total_paid ?? 0;
        $payment->total_due = $payment->total_due ?? 0;
    }

    private function generateUniqueSlug(): string
    {
        $lastExpenseId = Payment::withTrashed()->latest('id')->value('id') ?? 0;
        return (string)($lastExpenseId + 1);
    }

    public function created(Payment $payment): void
    {
    }

    public function updating(Payment $payment): void
    {
    }

    public function updated(Payment $payment): void
    {
    }

    public function saving(Payment $payment): void
    {
    }

    public function saved(Payment $payment): void
    {
    }

    public function deleting(Payment $payment): void
    {
    }

    public function deleted(Payment $payment): void
    {
    }

    public function restoring(Payment $payment): void
    {
    }

    public function restored(Payment $payment): void
    {
    }

    public function retrieved(Payment $payment): void
    {
    }

    public function forceDeleting(Payment $payment): void
    {
    }

    public function forceDeleted(Payment $payment): void
    {
    }

    public function replicating(Payment $payment): void
    {
    }
}
