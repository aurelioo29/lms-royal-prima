<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlanEvent\PlanEventStoreRequest;
use App\Http\Requests\PlanEvent\PlanEventUpdateRequest;
use App\Models\AnnualPlan;
use App\Models\Course;
use App\Models\PlanEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PlanEventController extends Controller
{
    private function assertEditable(AnnualPlan $plan): void
    {
        abort_unless(auth()->user()->canCreatePlans(), 403);
        abort_unless($plan->isDraft() || $plan->isRejected(), 403);
    }

    private function courseOptions()
    {
        // kamu bisa ganti status filter sesuai sistemmu:
        // published / approved / etc.
        return Course::query()
            ->where('status', 'published')
            ->orderBy('title')
            ->get(['id', 'title']);
    }

    public function create(AnnualPlan $annualPlan): View
    {
        $this->assertEditable($annualPlan);

        $planEvent = new PlanEvent(); // biar form partial bisa pakai $planEvent
        $courses = $this->courseOptions();

        return view('plan-events.create', compact('annualPlan', 'planEvent', 'courses'));
    }

    public function store(PlanEventStoreRequest $request, AnnualPlan $annualPlan): RedirectResponse
    {
        $this->assertEditable($annualPlan);

        // Jangan percaya annual_plan_id dari request (kalau ada) → force dari route
        $data = $request->validated();
        $data['annual_plan_id'] = $annualPlan->id;

        $annualPlan->events()->create($data);

        return redirect()
            ->route('annual-plans.show', $annualPlan)
            ->with('success', 'Event ditambahkan.');
    }

    public function edit(AnnualPlan $annualPlan, PlanEvent $planEvent): View
    {
        $this->assertEditable($annualPlan);
        abort_unless($planEvent->annual_plan_id === $annualPlan->id, 404);

        $courses = $this->courseOptions();

        return view('plan-events.edit', compact('annualPlan', 'planEvent', 'courses'));
    }

    public function update(
        PlanEventUpdateRequest $request,
        AnnualPlan $annualPlan,
        PlanEvent $planEvent
    ): RedirectResponse {
        $this->assertEditable($annualPlan);
        abort_unless($planEvent->annual_plan_id === $annualPlan->id, 404);

        $data = $request->validated();
        unset($data['annual_plan_id']); // extra safety

        $planEvent->update($data);

        return redirect()
            ->route('annual-plans.show', $annualPlan)
            ->with('success', 'Event diupdate.');
    }

    public function destroy(AnnualPlan $annualPlan, PlanEvent $planEvent): RedirectResponse
    {
        $this->assertEditable($annualPlan);
        abort_unless($planEvent->annual_plan_id === $annualPlan->id, 404);

        $planEvent->delete();

        return redirect()
            ->route('annual-plans.show', $annualPlan)
            ->with('success', 'Event dihapus.');
    }
}
