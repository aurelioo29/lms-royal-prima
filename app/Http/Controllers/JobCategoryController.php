<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobCategory\StoreJobCategoryRequest;
use App\Http\Requests\JobCategory\UpdateJobCategoryRequest;
use App\Models\JobCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $categories = JobCategory::query()
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('job_categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('job_categories.create');
    }

    public function store(StoreJobCategoryRequest $request): RedirectResponse
    {
        JobCategory::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'is_active' => (bool) $request->boolean('is_active'),
        ]);

        return redirect()->route('job-categories.index')->with('success', 'Job Category berhasil dibuat.');
    }

    public function edit(JobCategory $job_category): View
    {
        return view('job_categories.edit', ['category' => $job_category]);
    }

    public function update(UpdateJobCategoryRequest $request, JobCategory $job_category): RedirectResponse
    {
        $job_category->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'is_active' => (bool) $request->boolean('is_active'),
        ]);

        return redirect()->route('job-categories.index')->with('success', 'Job Category berhasil diupdate.');
    }

    public function destroy(JobCategory $job_category): RedirectResponse
    {
        if ($job_category->jobTitles()->exists()) {
            return back()->with('error', 'Tidak bisa hapus: masih ada Job Title di kategori ini.');
        }

        $job_category->delete();
        return redirect()->route('job-categories.index')->with('success', 'Job Category berhasil dihapus.');
    }

    public function show(JobCategory $job_category): View
    {
        return view('job_categories.show', ['category' => $job_category]);
    }
}
