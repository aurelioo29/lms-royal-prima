<x-app-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="h-1 w-full bg-[#121293]"></div>
                <div class="p-5 sm:p-6">
                    <h1 class="text-xl font-semibold text-slate-900">Tambah Plan Event</h1>
                    <p class="text-sm text-slate-600 mt-1">Event dibuat dulu (draft). Approval belakangan.</p>
                </div>

                <form method="POST" action="{{ route('annual-plans.events.store', $annualPlan) }}"
                    class="p-5 sm:p-6 space-y-4">
                    @csrf

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Title</label>
                        <input name="title" value="{{ old('title') }}"
                            class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        @error('title')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Description</label>
                        <textarea name="description" rows="4" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Start Date</label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">End Date</label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Start Time (opsional)</label>
                            <input type="time" name="start_time" value="{{ old('start_time') }}"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">End Time (opsional)</label>
                            <input type="time" name="end_time" value="{{ old('end_time') }}"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Location</label>
                            <input name="location" value="{{ old('location') }}"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Target Audience</label>
                            <input name="target_audience" value="{{ old('target_audience') }}"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Mode</label>
                            <select name="mode"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                                <option value="">—</option>
                                <option value="offline" @selected(old('mode') === 'offline')>Offline</option>
                                <option value="online" @selected(old('mode') === 'online')>Online</option>
                                <option value="blended" @selected(old('mode') === 'blended')>Blended</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Meeting Link (opsional)</label>
                            <input name="meeting_link" value="{{ old('meeting_link') }}"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('annual-plans.show', $annualPlan) }}"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Batal
                        </a>
                        <button
                            class="rounded-xl bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                            Simpan Event
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
