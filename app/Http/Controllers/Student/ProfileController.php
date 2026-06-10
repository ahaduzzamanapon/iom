<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('student.profile.index');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'phone'            => 'nullable|string|max:20',
            'guardian_name'    => 'nullable|string|max:255',
            'guardian_phone'   => 'nullable|string|max:20',
            'address'          => 'nullable|string',
            'password'         => 'nullable|min:8|confirmed',
            'current_password' => 'nullable|required_with:password|string',
        ]);

        // Verify current password before allowing change
        if (!empty($data['password'])) {
            if (empty($data['current_password']) || !Hash::check($data['current_password'], auth()->user()->password)) {
                return back()->withErrors(['current_password' => 'বর্তমান পাসওয়ার্ড সঠিক নয়।']);
            }
        }

        $profile = auth()->user()->studentProfile;
        if ($profile) {
            $profile->update([
                'phone'          => $data['phone'] ?? $profile->phone,
                'guardian_name'  => $data['guardian_name'] ?? $profile->guardian_name,
                'guardian_phone' => $data['guardian_phone'] ?? $profile->guardian_phone,
                'address'        => $data['address'] ?? $profile->address,
            ]);
        }

        if (!empty($data['password'])) {
            auth()->user()->update(['password' => Hash::make($data['password'])]);
        }

        return back()->with('success', 'Profile আপডেট হয়েছে।');
    }
}
