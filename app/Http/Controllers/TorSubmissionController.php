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
    private function ensureTorIsAllowed(PlanEvent $planEvent): void
    {
        // 1) Annual Plan harus approved
        if (!$planEvent->annualPlan?->isApproved()) {
            abort(403, 'Annual Plan belum di-ACC Direktur. TOR belum bisa dibuat.');
        }

        // 2) Plan Event harus approved
        if (!$planEvent->isApproved()) {
            abort(403, 'Plan Event belum di-ACC Direktur. TOR belum bisa dibuat.');
        }
    }

    public function create(PlanEvent $planEvent): View
    {
        $this->ensureTorIsAllowed($planEvent);

        return view('tor-submissions.create', [
            'event' => $planEvent,
        ]);
    }

    public function store(TorSubmissionStoreRequest $request): RedirectResponse
    {
        $planEvent = PlanEvent::with('annualPlan')->findOrFail((int) $request->input('plan_event_id'));
        $this->ensureTorIsAllowed($planEvent);

        // Kalau kamu mau 1 event = 1 TOR, ini wajib biar ga dobel
        if ($planEvent->torSubmission()->exists()) {
            return back()->with('error', 'TOR untuk Plan Event ini sudah ada.');
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('tor', 'public');
        }

        $tor = TorSubmission::create([
            'plan_event_id' => $planEvent->id,
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'file_path' => $filePath,
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->route('annual-plans.show', $planEvent->annual_plan_id)
            ->with('success', 'TOR berhasil dibuat (Draft).');
    }

    public function edit(TorSubmission $tor_submission): View
    {
        $tor_submission->load(['planEvent.annualPlan', 'reviewer', 'creator']);

        // Biar aman: kalau plan/event turun statusnya, TOR tetap bisa dibuka untuk lihat
        return view('tor-submissions.edit', ['tor' => $tor_submission]);
    }

    public function update(Request $request, TorSubmission $tor_submission): RedirectResponse
    {
        $tor_submission->load('planEvent.annualPlan');

        // Draft/rejected boleh edit oleh creator, aturan detailnya kamu bisa ketatkan pakai policy
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
        $tor_submission->load('planEvent.annualPlan');

        // Gate lagi biar nggak bisa “submit” kalau plan/event turun status
        $this->ensureTorIsAllowed($tor_submission->planEvent);

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
