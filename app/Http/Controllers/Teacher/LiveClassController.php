<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassLesson;
use App\Models\Batch;
use App\Models\Module;
use App\Services\ZoomService;
use App\Services\GoogleMeetService;
use Illuminate\Http\Request;
use Exception;

class LiveClassController extends Controller
{
    private function myBatchIds(): array
    {
        return Batch::whereHas('subjects', fn($q) =>
            $q->where('batch_subjects.teacher_id', auth()->id())
        )->pluck('id')->toArray();
    }

    private function mySubjectIds(): array
    {
        return \DB::table('batch_subjects')
            ->where('teacher_id', auth()->id())
            ->pluck('subject_id')
            ->unique()
            ->toArray();
    }

    public function index()
    {
        $liveClasses = ClassLesson::with(['batch.course', 'module.subject'])
            ->where('type', 'live')
            ->where('created_by', auth()->id())
            ->latest('scheduled_at')
            ->paginate(15);

        return view('teacher.live-class.index', compact('liveClasses'));
    }

    public function create()
    {
        $batches = Batch::whereIn('id', $this->myBatchIds())->where('status', 'active')->with('course')->get();
        $modules = Module::whereIn('subject_id', $this->mySubjectIds())->where('status', 'active')->with('subject')->get();
        return view('teacher.classes.live-class', compact('batches', 'modules'));
    }

    /**
     * Schedule a live class with auto-generated meeting link.
     * Platform: 'zoom' | 'google_meet' | 'manual'
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'batch_id'       => 'required|exists:batches,id',
            'module_id'      => 'required|exists:modules,id',
            'title'          => 'required|string|max:255',
            'platform'       => 'required|in:zoom,google_meet,manual',
            'meeting_link'   => 'nullable|url|max:500',  // only if manual
            'scheduled_at'   => 'required|date',
            'duration_mins'  => 'nullable|integer|min:1',
        ]);

        abort_if(!in_array($data['batch_id'], $this->myBatchIds()), 403, 'You are not assigned to this batch.');

        $module = Module::findOrFail($data['module_id']);
        abort_if(!in_array($module->subject_id, $this->mySubjectIds()), 403, 'You are not assigned to teach this subject.');

        // Scheduled time must be in the future
        if (\Carbon\Carbon::parse($data['scheduled_at'])->lt(now())) {
            return back()->withInput()->with('error', 'Scheduled time অতীতের হতে পারবে না। ভবিষ্যতের সময় দিন।');
        }

        // Manual platform requires a link
        if ($data['platform'] === 'manual' && empty($data['meeting_link'])) {
            return back()->withInput()->with('error', 'Manual platform নির্বাচন করলে Meeting Link দিতে হবে।');
        }

        $platform    = $data['platform'];
        $title       = $data['title'];
        $scheduledAt = $data['scheduled_at'];
        $duration    = $data['duration_mins'] ?? 60;

        $zoomLink      = null;
        $meetLink      = null;
        $zoomMeetingId = null;
        $zoomStartUrl  = null;

        try {
            if ($platform === 'zoom') {
                // Convert to UTC ISO8601 for Zoom API
                $startUtc = \Carbon\Carbon::parse($scheduledAt, 'Asia/Dhaka')
                    ->setTimezone('UTC')
                    ->format('Y-m-d\TH:i:s');

                $result        = app(ZoomService::class)->createMeeting($title, $startUtc, $duration);
                $zoomLink      = $result['join_url'];
                $zoomMeetingId = $result['meeting_id'];
                $zoomStartUrl  = $result['start_url'];

            } elseif ($platform === 'google_meet') {
                $startDhaka = \Carbon\Carbon::parse($scheduledAt, 'Asia/Dhaka');
                $endDhaka   = $startDhaka->copy()->addMinutes($duration);

                $result    = app(GoogleMeetService::class)->createMeeting(
                    $title,
                    $startDhaka->toIso8601String(),
                    $endDhaka->toIso8601String(),
                    'Asia/Dhaka'
                );
                $meetLink = $result['meet_link'];

            } else {
                // manual — teacher provides their own link
                $zoomLink = $data['meeting_link'] ?? null;
                $meetLink = $data['meeting_link'] ?? null;
            }

        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Meeting তৈরি করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }

        ClassLesson::create([
            'batch_id'         => $data['batch_id'],
            'module_id'        => $data['module_id'],
            'title'            => $title,
            'type'             => 'live',
            'scheduled_at'     => $scheduledAt,
            'duration_mins'    => $duration,
            'meeting_provider' => $platform,
            'zoom_link'        => $zoomLink,
            'meet_link'        => $meetLink,
            'zoom_meeting_id'  => $zoomMeetingId,
            'zoom_start_url'   => $zoomStartUrl,
            'is_published'     => true,
            'created_by'       => auth()->id(),
        ]);

        return back()->with('success', 'Live class schedule হয়েছে। ' .
            ($platform !== 'manual' ? 'Meeting link auto-generate হয়েছে ✓' : ''));
    }

    public function destroy(ClassLesson $lesson)
    {
        // Only the creator can delete
        if ($lesson->created_by !== auth()->id()) {
            return back()->with('error', 'Permission নেই।');
        }
        $lesson->delete();
        return back()->with('success', 'Live class মুছে ফেলা হয়েছে।');
    }
}
