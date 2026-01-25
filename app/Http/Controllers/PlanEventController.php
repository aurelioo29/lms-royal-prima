<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlanEvent\PlanEventStoreRequest;
use App\Http\Requests\PlanEvent\PlanEventUpdateRequest;
use App\Models\AnnualPlan;
use App\Models\PlanEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlanEventController extends Controller
{
    /**
     * Kabid boleh manage event selama Annual Plan bukan "pending".
     * Sesuai flow:
     * - Event boleh dibuat saat plan draft/rejected (sebelum plan ACC)
     * - Setelah plan approved, event masih boleh ditambah/diubah (selama event masih draft/rejected)
     */
    private function assertCanManageEvents(AnnualPlan $plan): void
    {
        $user = auth()->user();

        // Direktur bebas
        if ($user->canApprovePlans()) {
            return;
        }

        // Kabid
        abort_unless($user->canCreatePlans(), 403);

        // Plan pending = lagi diajukan, jangan diutak-atik dulu
        abort_unless($plan->status !== 'pending', 403);
    }

    /**
     * Kabid hanya boleh edit/hapus event kalau event masih draft/rejected.
     * Direktur bebas.
     */
    private function assertEventEditableByKabid(PlanEvent $event): void
    {
        $user = auth()->user();

        if ($user->canApprovePlans()) {
            return;
        }

        abort_unless($user->canCreatePlans(), 403);
        abort_unless(in_array($event->status, ['draft', 'rejected'], true), 403);
    }

    public function show(AnnualPlan $annualPlan, PlanEvent $planEvent): View
    {
        // kalau plan belum approved, yang boleh lihat cuma Kabid/Direktur
        $user = auth()->user();
        if (!$annualPlan->isApproved() && !($user->canCreatePlans() || $user->canApprovePlans())) {
            abort(403);
        }

        $planEvent->load([
            'annualPlan',
            'torSubmission.course',
            'creator',
            'approver',
        ]);

        return view('annual-plans.events.show', compact('annualPlan', 'planEvent'));
    }

    public function create(AnnualPlan $annualPlan): View
    {
        $this->assertCanManageEvents($annualPlan);

        $planEvent = new PlanEvent();

        return view('annual-plans.events.create', compact('annualPlan', 'planEvent'));
    }

    public function store(PlanEventStoreRequest $request, AnnualPlan $annualPlan): RedirectResponse
    {
        $this->assertCanManageEvents($annualPlan);

        $data = $request->validated();

        $data['annual_plan_id'] = $annualPlan->id;
        $data['created_by'] = auth()->id();
        $data['status'] = $data['status'] ?? 'draft';

        $annualPlan->events()->create($data);

        return redirect()
            ->route('annual-plans.show', $annualPlan)
            ->with('success', 'Event ditambahkan.');
    }

    public function edit(AnnualPlan $annualPlan, PlanEvent $planEvent): View
    {
        $this->assertCanManageEvents($annualPlan);
        $this->assertEventEditableByKabid($planEvent);

        return view('annual-plans.events.edit', compact('annualPlan', 'planEvent'));
    }

    public function update(
        PlanEventUpdateRequest $request,
        AnnualPlan $annualPlan,
        PlanEvent $planEvent
    ): RedirectResponse {
        $this->assertCanManageEvents($annualPlan);
        $this->assertEventEditableByKabid($planEvent);

        $data = $request->validated();
        unset($data['annual_plan_id']);

        $planEvent->update($data);

        return redirect()
            ->route('annual-plans.show', $annualPlan)
            ->with('success', 'Event diupdate.');
    }

    public function destroy(AnnualPlan $annualPlan, PlanEvent $planEvent): RedirectResponse
    {
        $this->assertCanManageEvents($annualPlan);
        $this->assertEventEditableByKabid($planEvent);

        $planEvent->delete();

        return redirect()
            ->route('annual-plans.show', $annualPlan)
            ->with('success', 'Event dihapus.');
    }

    /**
     * Kabid ajukan event hanya kalau Annual Plan sudah approved.
     */
    public function submit(AnnualPlan $annualPlan, PlanEvent $planEvent): RedirectResponse
    {
        abort_unless(auth()->user()->canCreatePlans(), 403);

        // (kalau kamu masih pakai rule ini)
        abort_unless($annualPlan->isApproved(), 403);

        abort_unless(in_array($planEvent->status, ['draft', 'rejected'], true), 403);

        // ✅ NEW: wajib punya TOR dulu
        $planEvent->load('torSubmission');

        if (!$planEvent->torSubmission) {
            return back()->with('error', 'Tidak bisa ajukan event: TOR belum dibuat. Silakan buat TOR dulu.');
        }

        // ✅ NEW: kalau kamu mau lebih ketat, TOR minimal submitted/approved
        if (!in_array($planEvent->torSubmission->status, ['submitted', 'approved'], true)) {
            return back()->with('error', 'Tidak bisa ajukan event: TOR masih draft. Silakan submit TOR dulu.');
        }

        $planEvent->update([
            'status' => 'pending',
            'submitted_at' => now(),
            'rejected_reason' => null,
            'rejected_at' => null,
            'approved_by' => null,
            'approved_at' => null,
        ]);

        return back()->with('success', 'Plan Event diajukan untuk approval.');
    }

    public function approve(AnnualPlan $annualPlan, PlanEvent $planEvent): RedirectResponse
    {
        abort_unless(auth()->user()->canApprovePlans(), 403);

        // biar konsisten: event di-approve hanya kalau annual plan approved
        abort_unless($annualPlan->isApproved(), 403);

        abort_unless(in_array($planEvent->status, ['pending', 'rejected'], true), 403);

        $planEvent->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejected_reason' => null,
            'rejected_at' => null,
        ]);

        return back()->with('success', 'Plan Event disetujui.');
    }

    public function reject(Request $request, AnnualPlan $annualPlan, PlanEvent $planEvent): RedirectResponse
    {
        abort_unless(auth()->user()->canApprovePlans(), 403);

        abort_unless(in_array($planEvent->status, ['pending', 'approved'], true), 403);

        $data = $request->validate([
            'rejected_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $planEvent->update([
            'status' => 'rejected',
            'approved_by' => null,
            'approved_at' => null,
            'rejected_at' => now(),
            'rejected_reason' => $data['rejected_reason'] ?: 'Perlu revisi.',
        ]);

        return back()->with('success', 'Plan Event ditolak.');
    }

    public function reopen(AnnualPlan $annualPlan, PlanEvent $planEvent): RedirectResponse
    {
        abort_unless(auth()->user()->canApprovePlans(), 403);

        $planEvent->update([
            'status' => 'draft',
            'approved_by' => null,
            'approved_at' => null,
            'rejected_reason' => null,
            'rejected_at' => null,
            'submitted_at' => null,
        ]);

        return back()->with('success', 'Plan Event dibuka kembali (Draft).');
    }

    public function approveAll(AnnualPlan $annualPlan, PlanEvent $planEvent): RedirectResponse
    {
        abort_unless(auth()->user()->canApprovePlans(), 403);

        // Consistent safety: event hanya di-approve kalau annual plan sudah approved
        abort_unless($annualPlan->isApproved(), 403);

        // Wajib load TOR
        $planEvent->load('torSubmission');

        $tor = $planEvent->torSubmission;

        if (!$tor) {
            return back()->with('error', 'Tidak bisa approve semua: TOR belum dibuat.');
        }

        // Optional: pastikan TOR minimal submitted/rejected sebelum di-approve
        if (!in_array($tor->status, ['submitted', 'rejected'], true) && $tor->status !== 'approved') {
            return back()->with('error', 'Tidak bisa approve semua: status TOR tidak valid untuk di-approve.');
        }

        // Event harus pending/rejected agar masuk akal untuk approve
        if (!in_array($planEvent->status, ['pending', 'rejected', 'approved'], true)) {
            return back()->with('error', 'Tidak bisa approve semua: status Event tidak valid untuk di-approve.');
        }

        DB::transaction(function () use ($tor, $planEvent) {

            // 1) Approve TOR kalau belum approved
            if ($tor->status !== 'approved') {
                $tor->update([
                    'status' => 'approved',
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                    'review_notes' => null,
                ]);
            }

            // 2) Approve Event kalau belum approved
            if ($planEvent->status !== 'approved') {
                $planEvent->update([
                    'status' => 'approved',
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                    'rejected_reason' => null,
                    'rejected_at' => null,
                ]);
            }
        });

        return back()->with('success', 'TOR dan Plan Event berhasil disetujui sekaligus.');
    }
}
