<?php

namespace App\Http\Controllers;

use App\Http\Requests\Course\CourseStoreRequest;
use App\Http\Requests\Course\CourseUpdateRequest;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\TorSubmission;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(auth()->user()->canCreateCourses(), 403);

        $q = $request->string('q')->toString();
        $status = $request->string('status')->toString(); // draft|published|archived|''

        $courses = Course::query()
            ->with([
                'type',
                'creator',
                'torSubmission.planEvent.annualPlan',
            ])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('enrollment_key', 'like', "%{$q}%")
                        ->orWhereHas('torSubmission.planEvent', function ($qe) use ($q) {
                            $qe->where('title', 'like', "%{$q}%")
                                ->orWhere('description', 'like', "%{$q}%");
                        })
                        ->orWhereHas('torSubmission.planEvent.annualPlan', function ($qa) use ($q) {
                            $qa->where('title', 'like', "%{$q}%")
                                ->orWhere('year', 'like', "%{$q}%");
                        });
                });
            })
            ->when($status, fn($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('courses.index', compact('courses', 'q', 'status'));
    }

    public function create(Request $request): View
    {
        abort_unless(auth()->user()->canCreateCourses(), 403);

        $prefillTorId = $request->integer('tor_submission_id');

        // hanya TOR approved, dan event approved
        $torOptions = TorSubmission::query()
            ->with(['planEvent.annualPlan'])
            ->where('status', 'approved')
            ->whereHas('planEvent', fn($q) => $q->where('status', 'approved'))
            // kalau kamu pakai rule 1 TOR = 1 Course
            ->whereDoesntHave('course')
            ->latest()
            ->get();

        $courseTypes = CourseType::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('courses.create', compact('torOptions', 'courseTypes', 'prefillTorId'));
    }

    public function store(CourseStoreRequest $request): RedirectResponse
    {
        abort_unless(auth()->user()->canCreateCourses(), 403);

        $tor = TorSubmission::with(['planEvent'])
            ->where('id', $request->integer('tor_submission_id'))
            ->firstOrFail();

        abort_unless($tor->status === 'approved', 403);
        abort_unless($tor->planEvent && $tor->planEvent->status === 'approved', 403);

        // kalau 1 TOR = 1 Course (recommended)
        if ($tor->course) {
            return back()->with('error', 'TOR ini sudah punya course.');
        }

        $course = Course::create([
            'tor_submission_id' => $tor->id,
            'course_type_id' => $request->input('course_type_id'),
            'tujuan' => $request->input('tujuan'),
            'training_hours' => $request->input('training_hours', 0),
            'status' => $request->input('status', 'draft'),
            'created_by' => auth()->id(),
            // enrollment_key auto-generated di model Course::booted()
        ]);

        return redirect()
            ->route('courses.edit', $course)
            ->with('success', 'Course dibuat. Enrollment Key: ' . $course->enrollment_key);
    }

    public function edit(Course $course): View
    {
        abort_unless(auth()->user()->canCreateCourses(), 403);

        $course->load([
            'type',
            'creator',
            'torSubmission.planEvent.annualPlan',
        ]);

        $courseTypes = CourseType::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('courses.edit', compact('course', 'courseTypes'));
    }

    public function update(CourseUpdateRequest $request, Course $course): RedirectResponse
    {
        abort_unless(auth()->user()->canCreateCourses(), 403);

        $course->update([
            'course_type_id' => $request->input('course_type_id'),
            'tujuan' => $request->input('tujuan'),
            'training_hours' => $request->input('training_hours', 0),
            'status' => $request->input('status', 'draft'),
        ]);

        return back()->with('success', 'Course diupdate.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        abort_unless(auth()->user()->canCreateCourses(), 403);

        $course->delete();

        return redirect()
            ->route('courses.index')
            ->with('success', 'Course dihapus.');
    }
}
