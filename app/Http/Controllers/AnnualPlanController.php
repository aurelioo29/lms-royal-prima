<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnnualPlan\AnnualPlanDecisionRequest;
use App\Http\Requests\AnnualPlan\AnnualPlanStoreRequest;
use App\Http\Requests\AnnualPlan\AnnualPlanUpdateRequest;
use App\Models\AnnualPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AnnualPlanController extends Controller
{
    public function index()
    {
        $q      = request('q');
        $status = request('status');
        $sort   = request('sort', 'newest');

        $plans = \App\Models\AnnualPlan::query()
            ->with(['creator', 'approver'])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('title', 'like', "%{$q}%")
                        ->orWhere('year', 'like', "%{$q}%")
                        ->orWhereHas('creator', fn($u) => $u->where('name', 'like', "%{$q}%"));
                });
            })
            ->when($status, fn($query) => $query->where('status', $status))
            ->when($sort === 'newest', fn($query) => $query->orderByDesc('created_at'))
            ->when($sort === 'oldest', fn($query) => $query->orderBy('created_at'))
            ->when($sort === 'year',   fn($query) => $query->orderByDesc('year')->orderByDesc('created_at'))
            ->paginate(10)
            ->withQueryString();

        return view('annual-plans.index', compact('plans', 'q', 'status', 'sort'));
    }


    public function show(AnnualPlan $annualPlan): View
    {
        $user = auth()->user();
        if (!$annualPlan->isApproved() && !($user->canCreatePlans() || $user->canApprovePlans())) {
            abort(403);
        }

        $annualPlan->load([
            'events' => fn($q) =>
            $q->orderBy('start_date')
                ->orderBy('start_time')
                ->with('torSubmission'),
        ]);

        return view('annual-plans.show', compact('annualPlan'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()->canCreatePlans(), 403);
        return view('annual-plans.create');
    }

    public function store(AnnualPlanStoreRequest $request): RedirectResponse
    {
        $plan = AnnualPlan::create([
            'year' => $request->year,
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('annual-plans.show', $plan)->with('success', 'Annual Plan dibuat (draft).');
    }

    public function edit(AnnualPlan $annualPlan): View
    {
        $user = auth()->user();

        // Director can edit any status
        if ($user->canApprovePlans()) {
            return view('annual-plans.edit', compact('annualPlan'));
        }

        // Kabid only draft/rejected
        abort_unless($user->canCreatePlans(), 403);
        abort_unless($annualPlan->isDraft() || $annualPlan->isRejected(), 403);

        return view('annual-plans.edit', compact('annualPlan'));
    }

    public function update(AnnualPlanUpdateRequest $request, AnnualPlan $annualPlan): RedirectResponse
    {
        $user = auth()->user();

        if ($user->canApprovePlans()) {
            $annualPlan->update($request->validated());
            return redirect()->route('annual-plans.show', $annualPlan)->with('success', 'Annual Plan diupdate oleh Direktur.');
        }

        abort_unless($user->canCreatePlans(), 403);
        abort_unless($annualPlan->isDraft() || $annualPlan->isRejected(), 403);

        $annualPlan->update($request->validated());

        return redirect()->route('annual-plans.show', $annualPlan)->with('success', 'Annual Plan diupdate.');
    }

    public function submit(AnnualPlan $annualPlan): RedirectResponse
    {
        abort_unless(auth()->user()->canCreatePlans(), 403);
        abort_unless($annualPlan->isDraft() || $annualPlan->isRejected(), 403);

        $annualPlan->update([
            'status' => 'pending',
            'submitted_at' => now(),
            'rejected_reason' => null,
            'rejected_at' => null,
            'approved_by' => null,
            'approved_at' => null,
        ]);

        return redirect()->route('annual-plans.show', $annualPlan)->with('success', 'Annual Plan dikirim untuk approval.');
    }

    public function approve(AnnualPlanDecisionRequest $request, AnnualPlan $annualPlan): RedirectResponse
    {
        abort_unless(auth()->user()->canApprovePlans(), 403);

        // allow approve from pending OR rejected
        abort_unless(in_array($annualPlan->status, ['pending', 'rejected']), 403);

        $annualPlan->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejected_reason' => null,
            'rejected_at' => null,
        ]);

        return redirect()->route('annual-plans.show', $annualPlan)->with('success', 'Annual Plan disetujui.');
    }

    public function reject(AnnualPlanDecisionRequest $request, AnnualPlan $annualPlan): RedirectResponse
    {
        abort_unless(auth()->user()->canApprovePlans(), 403);

        // allow reject from pending OR approved
        abort_unless(in_array($annualPlan->status, ['pending', 'approved']), 403);

        $annualPlan->update([
            'status' => 'rejected',
            'approved_by' => null,
            'approved_at' => null,
            'rejected_at' => now(),
            'rejected_reason' => $request->rejected_reason ?: 'Perlu revisi.',
        ]);

        return redirect()->route('annual-plans.show', $annualPlan)->with('success', 'Annual Plan ditolak.');
    }

    public function approvals(): View
    {
        $plans = AnnualPlan::query()
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);

        return view('annual-plans.approvals', compact('plans'));
    }

    public function reopen(AnnualPlan $annualPlan): RedirectResponse
    {
        abort_unless(auth()->user()->canApprovePlans(), 403);

        // up to you: set to draft or pending
        $annualPlan->update([
            'status' => 'draft',
            'approved_by' => null,
            'approved_at' => null,
            'rejected_reason' => null,
            'rejected_at' => null,
            'submitted_at' => null,
        ]);

        return redirect()->route('annual-plans.show', $annualPlan)
            ->with('success', 'Annual Plan dibuka kembali untuk revisi (Draft).');
    }
}
