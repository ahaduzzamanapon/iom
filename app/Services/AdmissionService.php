<?php

namespace App\Services;

use App\Models\Admission;
use App\Models\Batch;
use App\Models\StudentBatch;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdmissionService
{
    /**
     * Approve an admission: create user account, student profile, batch enrollment.
     * Returns ['user' => User, 'student_id' => string, 'password' => string]
     */
    public function approve(Admission $admission, int $batchId): array
    {
        // Generate Student ID
        $count     = StudentProfile::count() + 1;
        $studentId = 'IOM-' . date('Y') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        // Temporary password
        $password = Str::random(8);

        // Create user
        $user = User::create([
            'name'     => $admission->applicant_name,
            'email'    => $admission->applicant_email,
            'password' => Hash::make($password),
        ]);
        $user->assignRole('student');

        // Student profile
        StudentProfile::create([
            'user_id'        => $user->id,
            'student_id'     => $studentId,
            'phone'          => $admission->applicant_phone,
            'date_of_birth'  => $admission->date_of_birth,
            'gender'         => $admission->gender,
            'address'        => $admission->address,
            'photo'          => $admission->photo,
            'guardian_name'  => $admission->guardian_name,
            'guardian_phone' => $admission->guardian_phone,
        ]);

        // Batch enrollment
        StudentBatch::create([
            'user_id'     => $user->id,
            'batch_id'    => $batchId,
            'enrolled_at' => now()->toDateString(),
        ]);

        // Update admission record
        $admission->update([
            'status'      => 'approved',
            'user_id'     => $user->id,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        // Send credentials email
        $this->sendCredentialsMail($user, $studentId, $password);

        return compact('user', 'studentId', 'password');
    }

    /**
     * Reject an admission with remarks.
     */
    public function reject(Admission $admission, string $remarks): void
    {
        $admission->update([
            'status'      => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'remarks'     => $remarks,
        ]);
    }

    /**
     * Send login credentials email to the newly approved student.
     */
    private function sendCredentialsMail(User $user, string $studentId, string $password): void
    {
        try {
            Mail::send('emails.admission-approved', [
                'user'      => $user,
                'studentId' => $studentId,
                'password'  => $password,
            ], function ($mail) use ($user) {
                $mail->to($user->email)
                     ->subject('IOM — ভর্তি অনুমোদন ও লগইন তথ্য');
            });
        } catch (\Throwable $e) {
            // Log but don't crash — credentials are shown in flash
            \Log::error('Admission credentials email failed: ' . $e->getMessage());
        }
    }
}
