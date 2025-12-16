<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseType\CourseTypeStoreRequest;
use App\Http\Requests\CourseType\CourseTypeUpdateRequest;
use App\Models\CourseType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseTypeController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->toString();
        $status = $request->string('status')->toString(); // active|inactive|all|null

        $types = CourseType::query()
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                        ->orWhere('slug', 'like', "%{$q}%");
                });
            })
            ->when($status === 'active', fn($query) => $query->where('is_active', true))
            ->when($status === 'inactive', fn($query) => $query->where('is_active', false))
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('course-types.index', compact('types', 'q', 'status'));
    }

    public function create(): View
    {
        return view('course-types.create');
    }

    public function store(CourseTypeStoreRequest $request): RedirectResponse
    {
        CourseType::create([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'description' => $request->input('description'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('course-types.index')
            ->with('success', 'Course Type berhasil dibuat.');
    }

    public function edit(CourseType $course_type): View
    {
        return view('course-types.edit', [
            'courseType' => $course_type,
        ]);
    }

    public function update(CourseTypeUpdateRequest $request, CourseType $course_type): RedirectResponse
    {
        $course_type->update([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'description' => $request->input('description'),
            'is_active' => $request->boolean('is_active', $course_type->is_active),
        ]);

        return redirect()
            ->route('course-types.index')
            ->with('success', 'Course Type berhasil diupdate.');
    }

    public function destroy(CourseType $course_type): RedirectResponse
    {
        // Amanin: kalau masih dipakai course, jangan hapus (opsional tapi recommended)
        $inUse = $course_type->courses()->exists();
        if ($inUse) {
            return back()->with('error', 'Course Type masih dipakai oleh course. Nonaktifkan saja.');
        }

        $course_type->delete();

        return redirect()
            ->route('course-types.index')
            ->with('success', 'Course Type berhasil dihapus.');
    }

    /**
     * Toggle aktif/nonaktif tanpa delete.
     */
    public function toggle(CourseType $course_type): RedirectResponse
    {
        $course_type->update([
            'is_active' => !$course_type->is_active,
        ]);

        return back()->with('success', 'Status Course Type berhasil diubah.');
    }
}
