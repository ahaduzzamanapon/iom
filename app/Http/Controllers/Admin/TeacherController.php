<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TeacherProfile;
use App\Models\Batch;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = TeacherProfile::with('user')
            ->when(request('status'), fn($q) => $q->where('status', request('status')))
            ->latest()->paginate(15);
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'phone'          => 'nullable|string|max:20',
            'qualification'  => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
        ]);

        $count     = TeacherProfile::count() + 1;
        $teacherId = 'IOM-T-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        $password  = Str::random(8);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($password),
        ]);
        $user->assignRole('teacher');

        TeacherProfile::create([
            'user_id'        => $user->id,
            'teacher_id'     => $teacherId,
            'phone'          => $request->phone,
            'qualification'  => $request->qualification,
            'specialization' => $request->specialization,
        ]);

        return redirect()->route('admin.teachers.index')
            ->with('success', "Teacher তৈরি হয়েছে! ID: {$teacherId} | Password: {$password}");
    }

    public function edit(TeacherProfile $teacher)
    {
        $teacher->load('user');
        return view('admin.teachers.form', compact('teacher'));
    }

    public function update(Request $request, TeacherProfile $teacher)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'nullable|string|max:20',
            'qualification'  => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'status'         => 'required|in:active,inactive',
        ]);
        $teacher->update($request->only(['phone','qualification','specialization','status']));
        $teacher->user->update(['name' => $request->name]);
        return redirect()->route('admin.teachers.index')->with('success', 'Teacher আপডেট হয়েছে।');
    }

    public function destroy(TeacherProfile $teacher)
    {
        $teacher->user->delete();
        return redirect()->route('admin.teachers.index')->with('success', 'Teacher মুছে ফেলা হয়েছে।');
    }
}
