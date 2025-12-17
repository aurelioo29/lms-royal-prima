<x-app-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">{{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">{{ session('error') }}</div>
            @endif

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="h-1 w-full bg-[#121293]"></div>

                <div class="p-5 sm:p-6 flex flex-col gap-4">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h1 class="text-xl font-semibold text-slate-900">Edit Plan Event</h1>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <x-status-badge :status="$planEvent->status" label="Event" />
                                <x-status-badge :status="$annualPlan->status" label="Plan" />
                            </div>

                            @if ($planEvent->isRejected() && $planEvent->rejected_reason)
                                <div class="mt-3 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-800">
                                    <div class="font-semibold">Catatan Penolakan:</div>
                                    <div>{{ $planEvent->rejected_reason }}</div>
                                </div>
                            @endif
                        </div>

                        <a href="{{ route('annual-plans.show', $annualPlan) }}"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Kembali
                        </a>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        @if (auth()->user()->canCreatePlans() && in_array($planEvent->status, ['draft', 'rejected'], true))
                            <form method="POST"
                                action="{{ route('annual-plans.events.submit', [$annualPlan, $planEvent]) }}">
                                @csrf
                                @method('PATCH')
                                <button
                                    class="rounded-xl bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                    Ajukan Event
                                </button>
                            </form>
                        @endif

                        @if (auth()->user()->canApprovePlans())
                            @if (in_array($planEvent->status, ['pending', 'rejected'], true))
                                <form method="POST"
                                    action="{{ route('annual-plans.events.approve', [$annualPlan, $planEvent]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        class="rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                        Approve Event
                                    </button>
                                </form>
                            @endif

                            @if (in_array($planEvent->status, ['pending', 'approved'], true))
                                <form method="POST"
                                    action="{{ route('annual-plans.events.reject', [$annualPlan, $planEvent]) }}"
                                    class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input name="rejected_reason" placeholder="Alasan (opsional)"
                                        class="w-56 rounded-xl border border-slate-200 px-3 py-2 text-sm" />
                                    <button
                                        class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                        Reject Event
                                    </button>
                                </form>
                            @endif

                            <form method="POST"
                                action="{{ route('annual-plans.events.reopen', [$annualPlan, $planEvent]) }}">
                                @csrf
                                <button
                                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                    Reopen (Draft)
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <form method="POST" action="{{ route('annual-plans.events.update', [$annualPlan, $planEvent]) }}"
                    class="p-5 sm:p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Title</label>
                        <input name="title" value="{{ old('title', $planEvent->title) }}"
                            class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Description</label>
                        <textarea name="description" rows="4" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">{{ old('description', $planEvent->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Start Date</label>
                            <input type="date" name="start_date"
                                value="{{ old('start_date', optional($planEvent->start_date)->format('Y-m-d')) }}"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">End Date</label>
                            <input type="date" name="end_date"
                                value="{{ old('end_date', optional($planEvent->end_date)->format('Y-m-d')) }}"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Start Time</label>
                            <input type="time" name="start_time"
                                value="{{ old('start_time', $planEvent->start_time) }}"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">End Time</label>
                            <input type="time" name="end_time" value="{{ old('end_time', $planEvent->end_time) }}"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Location</label>
                            <input name="location" value="{{ old('location', $planEvent->location) }}"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Target Audience</label>
                            <input name="target_audience"
                                value="{{ old('target_audience', $planEvent->target_audience) }}"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Mode</label>
                            <select name="mode"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                                <option value="">—</option>
                                <option value="offline" @selected(old('mode', $planEvent->mode) === 'offline')>Offline</option>
                                <option value="online" @selected(old('mode', $planEvent->mode) === 'online')>Online</option>
                                <option value="blended" @selected(old('mode', $planEvent->mode) === 'blended')>Blended</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Meeting Link</label>
                            <input name="meeting_link" value="{{ old('meeting_link', $planEvent->meeting_link) }}"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <button
                            class="rounded-xl bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                            Update Event
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
