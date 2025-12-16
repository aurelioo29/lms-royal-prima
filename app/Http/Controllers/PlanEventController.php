<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlanEvent\PlanEventStoreRequest;
use App\Http\Requests\PlanEvent\PlanEventUpdateRequest;
use App\Models\AnnualPlan;
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

    public function create(AnnualPlan $annualPlan): View
    {
        $this->assertEditable($annualPlan);

        $planEvent = new PlanEvent();

        return view('plan-events.create', compact('annualPlan', 'planEvent'));
    }

    public function store(PlanEventStoreRequest $request, AnnualPlan $annualPlan): RedirectResponse
    {
        $this->assertEditable($annualPlan);

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

        return view('plan-events.edit', compact('annualPlan', 'planEvent'));
    }

    public function update(
        PlanEventUpdateRequest $request,
        AnnualPlan $annualPlan,
        PlanEvent $planEvent
    ): RedirectResponse {
        $this->assertEditable($annualPlan);

        $data = $request->validated();
        unset($data['annual_plan_id']);

        $planEvent->update($data);

        return redirect()
            ->route('annual-plans.show', $annualPlan)
            ->with('success', 'Event diupdate.');
    }

    public function destroy(AnnualPlan $annualPlan, PlanEvent $planEvent): RedirectResponse
    {
        $this->assertEditable($annualPlan);

        $planEvent->delete();

        return redirect()
            ->route('annual-plans.show', $annualPlan)
            ->with('success', 'Event dihapus.');
    }
}
