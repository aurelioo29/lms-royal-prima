<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobTitle\StoreJobTitleRequest;
use App\Http\Requests\JobTitle\UpdateJobTitleRequest;
use App\Models\JobCategory;
use App\Models\JobTitle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobTitleController extends Controller
{
    public function index(Request $request): View
    {
        $titles = JobTitle::query()
            ->with('jobCategory')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('job_titles.index', compact('titles'));
    }

    public function create(): View
    {
        $categories = JobCategory::orderBy('name')->get();
        return view('job_titles.create', compact('categories'));
    }

    public function store(StoreJobTitleRequest $request): RedirectResponse
    {
        JobTitle::create([
            'job_category_id' => $request->job_category_id,
            'name' => $request->name,
            'slug' => $request->slug,
            'is_active' => (bool) $request->boolean('is_active'),
        ]);

        return redirect()->route('job-titles.index')->with('success', 'Job Title berhasil dibuat.');
    }

    public function edit(JobTitle $job_title): View
    {
        $categories = JobCategory::orderBy('name')->get();
        return view('job_titles.edit', ['title' => $job_title, 'categories' => $categories]);
    }

    public function update(UpdateJobTitleRequest $request, JobTitle $job_title): RedirectResponse
    {
        $job_title->update([
            'job_category_id' => $request->job_category_id,
            'name' => $request->name,
            'slug' => $request->slug,
            'is_active' => (bool) $request->boolean('is_active'),
        ]);

        return redirect()->route('job-titles.index')->with('success', 'Job Title berhasil diupdate.');
    }

    public function destroy(JobTitle $job_title): RedirectResponse
    {
        $job_title->delete();
        return redirect()->route('job-titles.index')->with('success', 'Job Title berhasil dihapus.');
    }

    public function show(JobTitle $job_title): View
    {
        $job_title->load('jobCategory');
        return view('job_titles.show', ['title' => $job_title]);
    }
}
