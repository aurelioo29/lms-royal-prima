@php
    $startTime = $planEvent->start_time ? \Carbon\Carbon::parse($planEvent->start_time)->format('H:i') : '';
    $endTime = $planEvent->end_time ? \Carbon\Carbon::parse($planEvent->end_time)->format('H:i') : '';
@endphp

<x-app-layout>
    <div class="py-6">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- Alerts --}}
            @if (session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800 text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                {{-- Header --}}
                <div class="px-5 py-4 sm:px-6 border-b border-slate-200">
                    <div class="flex flex-col gap-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h1 class="text-lg font-semibold text-slate-900">Edit Plan Event</h1>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <x-status-badge :status="$planEvent->status" label="Event" />
                                    <x-status-badge :status="$annualPlan->status" label="Plan" />
                                </div>

                                @if ($planEvent->isRejected() && $planEvent->rejected_reason)
                                    <div
                                        class="mt-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                                        <div class="font-semibold">Catatan Penolakan</div>
                                        <div class="mt-1">{{ $planEvent->rejected_reason }}</div>
                                    </div>
                                @endif
                            </div>

                            <a href="{{ route('annual-plans.show', $annualPlan) }}"
                                class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Kembali
                            </a>
                        </div>

                        {{-- Action Toolbar --}}
                        <div class="flex flex-wrap items-center gap-2">
                            @if (auth()->user()->canCreatePlans() && in_array($planEvent->status, ['draft', 'rejected'], true))
                                <form method="POST"
                                    action="{{ route('annual-plans.events.submit', [$annualPlan, $planEvent]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        class="inline-flex items-center justify-center rounded-lg bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
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
                                            class="inline-flex items-center justify-center rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                            Approve Event
                                        </button>
                                    </form>
                                @endif

                                @if (in_array($planEvent->status, ['pending', 'approved'], true))
                                    <form method="POST"
                                        action="{{ route('annual-plans.events.reject', [$annualPlan, $planEvent]) }}"
                                        class="flex flex-wrap items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input name="rejected_reason" placeholder="Alasan (opsional)"
                                            class="w-full sm:w-64 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm
                                                   focus:outline-none focus:ring-2 focus:ring-red-500/10" />
                                        <button
                                            class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-100">
                                            Reject
                                        </button>
                                    </form>
                                @endif

                                <form method="POST"
                                    action="{{ route('annual-plans.events.reopen', [$annualPlan, $planEvent]) }}">
                                    @csrf
                                    <button
                                        class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                        Reopen (Draft)
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('annual-plans.events.update', [$annualPlan, $planEvent]) }}"
                    class="px-5 py-5 sm:px-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="title" class="block text-sm font-semibold text-slate-700">Judul Event</label>
                        <input id="title" name="title" value="{{ old('title', $planEvent->title) }}"
                            class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800
                                   focus:outline-none focus:ring-2 focus:ring-[#121293]/20">
                        @error('title')
                            <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-slate-700">Deskripsi</label>
                        <textarea id="description" name="description" rows="5"
                            class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800
                                   focus:outline-none focus:ring-2 focus:ring-[#121293]/20">{{ old('description', $planEvent->description) }}</textarea>
                        @error('description')
                            <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-semibold text-slate-700">Tanggal
                                Mulai</label>
                            <input id="start_date" type="date" name="start_date"
                                value="{{ old('start_date', optional($planEvent->start_date)->format('Y-m-d')) }}"
                                class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800
                                       focus:outline-none focus:ring-2 focus:ring-[#121293]/20">
                            @error('start_date')
                                <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-semibold text-slate-700">Tanggal
                                Selesai</label>
                            <input id="end_date" type="date" name="end_date"
                                value="{{ old('end_date', optional($planEvent->end_date)->format('Y-m-d')) }}"
                                class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800
                                       focus:outline-none focus:ring-2 focus:ring-[#121293]/20">
                            @error('end_date')
                                <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="start_time" class="block text-sm font-semibold text-slate-700">Jam Mulai</label>
                            <p class="mt-1 text-xs text-slate-500">Opsional. Format: HH:MM</p>
                            <input id="start_time" type="text" inputmode="numeric" placeholder="08:00"
                                name="start_time" value="{{ old('start_time', $startTime) }}" data-timepicker
                                class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800
                                       focus:outline-none focus:ring-2 focus:ring-[#121293]/20">
                            @error('start_time')
                                <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="end_time" class="block text-sm font-semibold text-slate-700">Jam Selesai</label>
                            <p class="mt-1 text-xs text-slate-500">Opsional. Format: HH:MM</p>
                            <input id="end_time" type="text" inputmode="numeric" placeholder="12:00" name="end_time"
                                value="{{ old('end_time', $endTime) }}" data-timepicker
                                class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800
                                       focus:outline-none focus:ring-2 focus:ring-[#121293]/20">
                            @error('end_time')
                                <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="location" class="block text-sm font-semibold text-slate-700">Lokasi</label>
                            <input id="location" name="location" value="{{ old('location', $planEvent->location) }}"
                                class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800
                                       focus:outline-none focus:ring-2 focus:ring-[#121293]/20">
                            @error('location')
                                <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="target_audience" class="block text-sm font-semibold text-slate-700">Target
                                Peserta</label>
                            <input id="target_audience" name="target_audience"
                                value="{{ old('target_audience', $planEvent->target_audience) }}"
                                class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800
                                       focus:outline-none focus:ring-2 focus:ring-[#121293]/20">
                            @error('target_audience')
                                <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="mode" class="block text-sm font-semibold text-slate-700">Mode</label>
                            <select id="mode" name="mode"
                                class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800
                                       focus:outline-none focus:ring-2 focus:ring-[#121293]/20">
                                <option value="">—</option>
                                <option value="offline" @selected(old('mode', $planEvent->mode) === 'offline')>Offline</option>
                                <option value="online" @selected(old('mode', $planEvent->mode) === 'online')>Online</option>
                                <option value="blended" @selected(old('mode', $planEvent->mode) === 'blended')>Blended</option>
                            </select>
                            @error('mode')
                                <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="meeting_link" class="block text-sm font-semibold text-slate-700">Meeting
                                Link</label>
                            <p id="meeting_help" class="mt-1 text-xs text-slate-500">Isi jika mode Online/Blended.</p>

                            <input id="meeting_link" name="meeting_link"
                                value="{{ old('meeting_link', $planEvent->meeting_link) }}" placeholder="https://"
                                class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 placeholder:text-slate-400
                                       focus:outline-none focus:ring-2 focus:ring-[#121293]/20
                                       disabled:bg-slate-50 disabled:text-slate-500 disabled:cursor-not-allowed">
                            @error('meeting_link')
                                <div class="mt-2 text-sm text-red-600">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-200 flex items-center justify-end gap-2">
                        <button
                            class="inline-flex items-center justify-center rounded-lg bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                            Update Event
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // end_date >= start_date
            const start = document.getElementById('start_date');
            const end = document.getElementById('end_date');

            if (start && end) {
                const syncMinEndDate = () => {
                    if (start.value) {
                        end.min = start.value;
                        if (end.value && end.value < start.value) end.value = start.value;
                    } else {
                        end.removeAttribute('min');
                    }
                };
                syncMinEndDate();
                start.addEventListener('change', syncMinEndDate);
            }

            // meeting_link enabled only for online/blended
            const mode = document.getElementById('mode');
            const link = document.getElementById('meeting_link');
            const help = document.getElementById('meeting_help');

            if (mode && link) {
                const syncMeetingLink = () => {
                    const v = (mode.value || '').toLowerCase();
                    const enabled = (v === 'online' || v === 'blended');

                    link.disabled = !enabled;

                    if (enabled) {
                        link.placeholder = 'https://';
                        if (help) help.textContent = 'Wajib diisi jika mode Online/Blended.';
                    } else {
                        link.placeholder = 'Tidak diperlukan untuk Offline';
                        link.value = '';
                        if (help) help.textContent = 'Tidak diperlukan untuk mode Offline.';
                    }
                };

                syncMeetingLink();
                mode.addEventListener('change', syncMeetingLink);
            }
        });
    </script>
</x-app-layout>
