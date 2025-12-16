<?php

namespace App\Http\Controllers;

use App\Http\Requests\Course\CourseStoreRequest;
use App\Http\Requests\Course\CourseUpdateRequest;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\TorSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->toString();
        $status = $request->string('status')->toString();

        $courses = Course::query()
            ->with(['type'])
            ->when($q, function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%");
            })
            ->when($status, fn($query) => $query->where('status', $status))
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('courses.index', compact('courses', 'q', 'status'));
    }

    public function create(Request $request): View
    {
        $torId = $request->input('tor_submission_id');

        $tor = null;
        if ($torId) {
            $tor = TorSubmission::query()->with('planEvent')->findOrFail($torId);
        }

        $types = CourseType::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('courses.create', compact('tor', 'types'));
    }

    public function store(CourseStoreRequest $request): RedirectResponse
    {
        $tor = TorSubmission::findOrFail($request->input('tor_submission_id'));

        if ($tor->status !== 'approved') {
            return back()->with('error', 'TOR belum approved. Course tidak bisa dibuat.');
        }

        // kalau kamu mau 1 TOR = 1 Course, cegah dobel:
        if ($tor->course()->exists()) {
            return back()->with('error', 'Course untuk TOR ini sudah ada.');
        }

        $course = Course::create([
            'tor_submission_id' => $tor->id,
            'course_type_id' => $request->input('course_type_id'),

            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'training_hours' => $request->input('training_hours'),

            'status' => $request->input('status', 'draft'),
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->route('courses.edit', $course)
            ->with('success', 'Course berhasil dibuat.');
    }

    public function edit(Course $course): View
    {
        $course->load(['type', 'torSubmission.planEvent', 'modules']);

        $types = CourseType::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('courses.edit', compact('course', 'types'));
    }

    public function update(CourseUpdateRequest $request, Course $course): RedirectResponse
    {
        $course->update([
            'course_type_id' => $request->input('course_type_id'),

            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'training_hours' => $request->input('training_hours'),

            'status' => $request->input('status'),
        ]);

        return back()->with('success', 'Course berhasil diupdate.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();
        return redirect()->route('courses.index')->with('success', 'Course berhasil dihapus.');
    }
}
