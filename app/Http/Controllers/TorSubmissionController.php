<?php

namespace App\Http\Controllers;

use App\Models\PlanEvent;
use App\Models\TorSubmission;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TorSubmissionController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->canCreateTOR(), 403);

        $tors = TorSubmission::query()
            ->with(['planEvent.annualPlan'])
            ->latest()
            ->paginate(10);

        return view('tor-submissions.index', compact('tors'));
    }

    public function create(PlanEvent $planEvent): View|RedirectResponse
    {
        abort_unless(auth()->user()->canCreateTOR(), 403);

        // TOR hanya boleh dibuat kalau event sudah APPROVED (sesuai UI kamu)
        abort_unless($planEvent->status === 'approved', 403);

        // kalau sudah ada TOR, lempar ke edit
        if ($planEvent->torSubmission) {
            return redirect()->route('tor-submissions.edit', $planEvent->torSubmission);
        }

        return view('tor-submissions.create', compact('planEvent'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'plan_event_id' => ['required', 'exists:plan_events,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ]);

        $tor = new TorSubmission();
        $tor->plan_event_id = $request->plan_event_id;
        $tor->title = $request->title;
        $tor->content = $request->content;

        // WAJIB, biar error created_by hilang juga:
        $tor->created_by = auth()->id();

        // AUTO SUBMIT (biar bukan draft)
        $tor->status = 'submitted';
        $tor->submitted_at = now();

        if ($request->hasFile('file')) {
            $tor->file_path = $request->file('file')->store('tors', 'public');
        }

        $tor->save();

        return redirect()
            ->route('tor-submissions.edit', $tor)
            ->with('success', 'TOR dibuat (Draft).');
    }

    public function edit(TorSubmission $torSubmission): View
    {
        abort_unless(auth()->user()->canCreateTOR() || auth()->user()->canApproveTOR(), 403);

        $torSubmission->load(['planEvent.annualPlan']);

        return view('tor-submissions.edit', [
            'tor' => $torSubmission, // ✅ biar blade kamu tetap pakai $tor
        ]);
    }

    public function update(Request $request, TorSubmission $torSubmission): RedirectResponse
    {
        abort_unless(auth()->user()->canCreateTOR(), 403);

        // kalau sudah approved, Kabid jangan edit lagi (opsional tapi biasanya begitu)
        abort_unless(in_array($torSubmission->status, ['draft', 'rejected', 'submitted'], true), 403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'max:5120'],
        ]);

        $torSubmission->title = $data['title'];
        $torSubmission->content = $data['content'] ?? null;

        if ($request->hasFile('file')) {
            $torSubmission->file_path = $request->file('file')->store('tors', 'public');
        }

        $torSubmission->save();

        return back()->with('success', 'TOR diupdate.');
    }

    public function approvals()
    {
        abort_unless(auth()->user()->canApprovePlans(), 403);

        $tors = \App\Models\TorSubmission::with(['planEvent.annualPlan', 'creator'])
            ->whereIn('status', ['submitted']) // kalau flow kamu memang submit dulu baru approve
            // ->whereIn('status', ['draft','submitted']) // kalau direktur mau lihat draft juga
            ->latest()
            ->paginate(15);

        return view('tor-submissions.approvals', compact('tors'));
    }

    public function approve(TorSubmission $torSubmission): RedirectResponse
    {
        abort_unless(auth()->user()->canApprovePlans(), 403);

        // optional safety: hanya boleh approve kalau status submitted/rejected
        abort_unless(in_array($torSubmission->status, ['submitted', 'rejected'], true), 403);

        $torSubmission->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => null,
        ]);

        return back()->with('success', 'TOR disetujui.');
    }
}
