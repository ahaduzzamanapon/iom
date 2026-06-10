<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassLesson;
use App\Models\Module;
use App\Models\Batch;
use App\Services\ZoomService;
use App\Services\GoogleMeetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;

class ClassLessonController extends Controller
{
    public function index()
    {
        $classes = ClassLesson::with(['module.subject','batch'])->latest()->paginate(15);
        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        $modules = Module::with('subject')->get();
        $batches = Batch::where('status','active')->get();
        return view('admin.classes.form', compact('modules','batches'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'module_id'        => 'required|exists:modules,id',
            'batch_id'         => 'required|exists:batches,id',
            'title'            => 'required|string|max:255',
            'title_bn'         => 'nullable|string|max:255',
            'type'             => 'required|in:video,live,pdf,note',
            'youtube_url'      => 'nullable|url',
            'meet_link'        => 'nullable|url',
            'zoom_link'        => 'nullable|url',
            'scheduled_at'     => 'nullable|date',
            'order'            => 'required|integer|min:1',
            'is_published'     => 'boolean',
            'publish_at'       => 'nullable|date',
            'meeting_provider' => 'nullable|in:zoom,google_meet,manual',
            'duration_mins'    => 'nullable|integer|min:1',
        ]);

        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('class-files', 'public');
        }

        // Validate module's subject is actually assigned to the chosen batch
        $module = \App\Models\Module::findOrFail($data['module_id']);
        $batchHasSubject = \DB::table('batch_subjects')
            ->where('batch_id', $data['batch_id'])
            ->where('subject_id', $module->subject_id)
            ->exists();
        if (!$batchHasSubject) {
            return back()->withInput()->with('error', 'নির্বাচিত মডিউলের বিষয় (Subject) এই ব্যাচে নেই। সঠিক মডিউল নির্বাচন করুন।');
        }

        $data['is_published'] = $request->boolean('is_published');
        $meetingProvider = $data['meeting_provider'] ?? 'manual';
        $data['meeting_provider'] = $meetingProvider;
        $duration = $data['duration_mins'] ?? 60;

        if ($data['type'] === 'live' && in_array($meetingProvider, ['zoom', 'google_meet'])) {
            if (empty($data['scheduled_at'])) {
                return back()->withInput()->with('error', 'Live class এর জন্য Scheduled At সিলেক্ট করা আবশ্যক।');
            }

            try {
                if ($meetingProvider === 'zoom') {
                    $startUtc = \Carbon\Carbon::parse($data['scheduled_at'], 'Asia/Dhaka')
                        ->setTimezone('UTC')
                        ->format('Y-m-d\TH:i:s');
                    $result = app(ZoomService::class)->createMeeting($data['title'], $startUtc, $duration);
                    $data['zoom_link'] = $result['join_url'];
                    $data['zoom_meeting_id'] = $result['meeting_id'];
                    $data['zoom_start_url'] = $result['start_url'];
                } elseif ($meetingProvider === 'google_meet') {
                    $startDhaka = \Carbon\Carbon::parse($data['scheduled_at'], 'Asia/Dhaka');
                    $endDhaka = $startDhaka->copy()->addMinutes($duration);
                    $result = app(GoogleMeetService::class)->createMeeting(
                        $data['title'],
                        $startDhaka->toIso8601String(),
                        $endDhaka->toIso8601String(),
                        'Asia/Dhaka'
                    );
                    $data['meet_link'] = $result['meet_link'];
                }
            } catch (Exception $e) {
                return back()->withInput()->with('error', 'Meeting তৈরি করতে সমস্যা হয়েছে: ' . $e->getMessage());
            }
        }

        ClassLesson::create($data);
        return redirect()->route('admin.classes.index')->with('success', 'Class তৈরি হয়েছে।');
    }

    public function edit(ClassLesson $class)
    {
        $modules = Module::with('subject')->get();
        $batches = Batch::where('status','active')->get();
        return view('admin.classes.form', compact('class','modules','batches'));
    }

    public function update(Request $request, ClassLesson $class)
    {
        $data = $request->validate([
            'module_id'        => 'required|exists:modules,id',
            'batch_id'         => 'required|exists:batches,id',
            'title'            => 'required|string|max:255',
            'title_bn'         => 'nullable|string|max:255',
            'type'             => 'required|in:video,live,pdf,note',
            'youtube_url'      => 'nullable|url',
            'meet_link'        => 'nullable|url',
            'zoom_link'        => 'nullable|url',
            'scheduled_at'     => 'nullable|date',
            'order'            => 'required|integer|min:1',
            'is_published'     => 'boolean',
            'publish_at'       => 'nullable|date',
            'meeting_provider' => 'nullable|in:zoom,google_meet,manual',
            'duration_mins'    => 'nullable|integer|min:1',
        ]);

        if ($request->hasFile('file_path')) {
            if ($class->file_path) Storage::disk('public')->delete($class->file_path);
            $data['file_path'] = $request->file('file_path')->store('class-files', 'public');
        }

        $data['is_published'] = $request->boolean('is_published');
        $meetingProvider = $data['meeting_provider'] ?? 'manual';
        $data['meeting_provider'] = $meetingProvider;
        $duration = $data['duration_mins'] ?? 60;

        if ($data['type'] === 'live' && in_array($meetingProvider, ['zoom', 'google_meet'])) {
            if (empty($data['scheduled_at'])) {
                return back()->withInput()->with('error', 'Live class এর জন্য Scheduled At সিলেক্ট করা আবশ্যক।');
            }

            try {
                if ($meetingProvider === 'zoom') {
                    $startUtc = \Carbon\Carbon::parse($data['scheduled_at'], 'Asia/Dhaka')
                        ->setTimezone('UTC')
                        ->format('Y-m-d\TH:i:s');
                    $result = app(ZoomService::class)->createMeeting($data['title'], $startUtc, $duration);
                    $data['zoom_link'] = $result['join_url'];
                    $data['zoom_meeting_id'] = $result['meeting_id'];
                    $data['zoom_start_url'] = $result['start_url'];
                } elseif ($meetingProvider === 'google_meet') {
                    $startDhaka = \Carbon\Carbon::parse($data['scheduled_at'], 'Asia/Dhaka');
                    $endDhaka = $startDhaka->copy()->addMinutes($duration);
                    $result = app(GoogleMeetService::class)->createMeeting(
                        $data['title'],
                        $startDhaka->toIso8601String(),
                        $endDhaka->toIso8601String(),
                        'Asia/Dhaka'
                    );
                    $data['meet_link'] = $result['meet_link'];
                }
            } catch (Exception $e) {
                return back()->withInput()->with('error', 'Meeting তৈরি করতে সমস্যা হয়েছে: ' . $e->getMessage());
            }
        }

        $class->update($data);
        return redirect()->route('admin.classes.index')->with('success', 'Class আপডেট হয়েছে।');
    }

    public function destroy(ClassLesson $class)
    {
        if ($class->file_path) Storage::disk('public')->delete($class->file_path);
        $class->delete();
        return redirect()->route('admin.classes.index')->with('success', 'Class মুছে ফেলা হয়েছে।');
    }
}

