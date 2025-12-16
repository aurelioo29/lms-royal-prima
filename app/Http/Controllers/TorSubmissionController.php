<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tor\TorSubmissionDecisionRequest;
use App\Http\Requests\Tor\TorSubmissionStoreRequest;
use App\Models\PlanEvent;
use App\Models\TorSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TorSubmissionController extends Controller
{
    public function create(PlanEvent $plan_event): View
    {
        return view('tor-submissions.create', [
            'event' => $plan_event,
        ]);
    }

    public function store(TorSubmissionStoreRequest $request): RedirectResponse
    {
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('tor', 'public');
        }

        $tor = TorSubmission::create([
            'plan_event_id' => (int) $request->input('plan_event_id'),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'file_path' => $filePath,

            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->route('plan-events.edit', $tor->plan_event_id)
            ->with('success', 'TOR berhasil dibuat (Draft).');
    }

    public function edit(TorSubmission $tor_submission): View
    {
        $tor_submission->load(['planEvent', 'reviewer', 'creator']);
        return view('tor-submissions.edit', ['tor' => $tor_submission]);
    }

    public function update(Request $request, TorSubmission $tor_submission): RedirectResponse
    {
        // simple update (kalau mau strict, bikin TorSubmissionUpdateRequest)
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ]);

        if ($request->hasFile('file')) {
            if ($tor_submission->file_path) {
                Storage::disk('public')->delete($tor_submission->file_path);
            }
            $data['file_path'] = $request->file('file')->store('tor', 'public');
        }

        $tor_submission->update($data);

        return back()->with('success', 'TOR berhasil diupdate.');
    }

    public function submit(TorSubmission $tor_submission): RedirectResponse
    {
        if (!in_array($tor_submission->status, ['draft', 'rejected'], true)) {
            return back()->with('error', 'TOR hanya bisa diajukan dari status draft/rejected.');
        }

        $tor_submission->update([
            'status' => 'submitted',
            'submitted_at' => now(),
            'reviewed_at' => null,
            'review_notes' => null,
        ]);

        return back()->with('success', 'TOR berhasil diajukan untuk ACC.');
    }

    public function approve(TorSubmissionDecisionRequest $request, TorSubmission $tor_submission): RedirectResponse
    {
        if ($tor_submission->status !== 'submitted') {
            return back()->with('error', 'TOR harus submitted untuk bisa di-approve.');
        }

        $tor_submission->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => $request->input('review_notes'),
        ]);

        return back()->with('success', 'TOR di-approve.');
    }

    public function reject(TorSubmissionDecisionRequest $request, TorSubmission $tor_submission): RedirectResponse
    {
        if ($tor_submission->status !== 'submitted') {
            return back()->with('error', 'TOR harus submitted untuk bisa di-reject.');
        }

        $tor_submission->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => $request->input('review_notes'),
        ]);

        return back()->with('success', 'TOR di-reject.');
    }
}
