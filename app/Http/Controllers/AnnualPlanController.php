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
    public function index(): View
    {
        $plans = AnnualPlan::query()
            ->with('creator', 'approver')
            ->orderByDesc('year')
            ->orderByDesc('id')
            ->paginate(10);

        return view('annual-plans.index', compact('plans'));
    }

    public function show(AnnualPlan $annualPlan): View
    {
        // semua user boleh lihat plan yang approved
        // kabid & direktur boleh lihat semua (buat review)
        $user = auth()->user();
        if (!$annualPlan->isApproved() && !($user->canCreatePlans() || $user->canApprovePlans())) {
            abort(403);
        }

        $annualPlan->load(['events' => fn($q) => $q->orderBy('date')->orderBy('start_time')]);

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
        abort_unless(auth()->user()->canCreatePlans(), 403);
        abort_unless($annualPlan->isDraft() || $annualPlan->isRejected(), 403);

        return view('annual-plans.edit', compact('annualPlan'));
    }

    public function update(AnnualPlanUpdateRequest $request, AnnualPlan $annualPlan): RedirectResponse
    {
        abort_unless(auth()->user()->canCreatePlans(), 403);
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
        abort_unless($annualPlan->isPending(), 403);

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
        abort_unless($annualPlan->isPending(), 403);

        $annualPlan->update([
            'status' => 'rejected',
            'approved_by' => null,
            'approved_at' => null,
            'rejected_at' => now(),
            'rejected_reason' => $request->rejected_reason ?: 'Perlu revisi.',
        ]);

        return redirect()->route('annual-plans.show', $annualPlan)->with('success', 'Annual Plan ditolak.');
    }

    public function approvals(): \Illuminate\View\View
    {
        $plans = \App\Models\AnnualPlan::query()
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);

        return view('annual-plans.approvals', compact('plans'));
    }
}
