<?php

namespace App\Services;

use App\Models\FeeStructure;
use App\Models\Payment;
use App\Models\StudentProfile;

class PaymentService
{
    /**
     * Record a manual payment made by admin.
     */
    public function recordManual(array $data): Payment
    {
        return Payment::create([
            'user_id'           => $data['user_id'],
            'fee_structure_id'  => $data['fee_structure_id'] ?? null,
            'amount'            => $data['amount'],
            'method'            => 'manual',
            'transaction_id'    => 'MAN-' . strtoupper(uniqid()),
            'status'            => 'paid',
            'paid_at'           => now(),
            'remarks'           => $data['remarks'] ?? null,
        ]);
    }

    /**
     * Get total paid amount for a student.
     */
    public function totalPaid(int $userId): float
    {
        return Payment::where('user_id', $userId)
            ->where('status', 'paid')
            ->sum('amount');
    }

    /**
     * Calculate total due for a student based on fee structures assigned to their batch.
     */
    public function totalDue(int $userId): float
    {
        $student = StudentProfile::where('user_id', $userId)->first();
        if (! $student) {
            return 0;
        }

        $batchId = $student->batches()->where('status', 'active')->value('batch_id');
        if (! $batchId) {
            return 0;
        }

        $totalFee = FeeStructure::where('batch_id', $batchId)->sum('amount');
        $paid     = $this->totalPaid($userId);

        return max(0, $totalFee - $paid);
    }

    /**
     * Check if a student has cleared their fees (no dues).
     */
    public function isFeeClear(int $userId): bool
    {
        return $this->totalDue($userId) <= 0;
    }

    /**
     * Generate a simple invoice number.
     */
    public function generateInvoiceNo(): string
    {
        return 'INV-' . date('Ymd') . '-' . str_pad(Payment::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
    }
}
