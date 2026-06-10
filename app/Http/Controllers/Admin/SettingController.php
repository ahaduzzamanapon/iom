<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::allKeyed();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'institute_name'     => 'required|string|max:255',
            'address'            => 'nullable|string',
            'phone'              => 'nullable|string|max:50',
            'email'              => 'nullable|email',
            'website'            => 'nullable|url',
            'logo'               => 'nullable|image|max:2048',
            'min_attendance'     => 'required|integer|min:0|max:100',
            // Grade mark boundaries (percentage thresholds, NOT GPA points)
            'gpa_a_plus'         => 'nullable|integer|min:0|max:100',  // A+ threshold (default 80)
            'gpa_a'              => 'nullable|integer|min:0|max:100',  // A  threshold (default 75)
            'gpa_a_minus'        => 'nullable|integer|min:0|max:100',  // A- threshold (default 70)
            'gpa_b_plus'         => 'nullable|integer|min:0|max:100',  // B+ threshold (default 65)
            'gpa_b'              => 'nullable|integer|min:0|max:100',  // B  threshold (default 60)
            'gpa_b_minus'        => 'nullable|integer|min:0|max:100',  // B- threshold (default 55)
            'gpa_c_plus'         => 'nullable|integer|min:0|max:100',  // C+ threshold (default 50)
            'gpa_c'              => 'nullable|integer|min:0|max:100',  // C  threshold (default 45)
            'gpa_d'              => 'nullable|integer|min:0|max:100',  // D  threshold (default 40)
        ]);

        $fields = ['institute_name','address','phone','email','website','min_attendance',
                   'gpa_a_plus','gpa_a','gpa_a_minus','gpa_b_plus','gpa_b','gpa_b_minus',
                   'gpa_c_plus','gpa_c','gpa_d'];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                SystemSetting::set($field, $request->input($field));
            }
        }

        if ($request->hasFile('logo')) {
            $old = SystemSetting::get('logo');
            if ($old && file_exists(public_path('uploads/logos/'.$old))) {
                unlink(public_path('uploads/logos/'.$old));
            }
            $filename = time().'.'.$request->file('logo')->getClientOriginalExtension();
            $request->file('logo')->move(public_path('uploads/logos'), $filename);
            SystemSetting::set('logo', $filename);
        }

        return back()->with('success', 'Settings আপডেট হয়েছে।');
    }
}
