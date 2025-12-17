<x-app-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="h-1 w-full bg-[#121293]"></div>

                <div class="p-5 sm:p-6 flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-xl font-semibold text-slate-900">Edit Annual Plan</h1>
                        <div class="mt-2">
                            <x-status-badge :status="$annualPlan->status" label="Plan" />
                        </div>
                        <p class="text-sm text-slate-600 mt-2">
                            Direktur boleh edit kapan saja. Kabid hanya draft/rejected.
                        </p>
                    </div>
                    <a href="{{ route('annual-plans.show', $annualPlan) }}"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Kembali
                    </a>
                </div>

                <form method="POST" action="{{ route('annual-plans.update', $annualPlan) }}"
                    class="p-5 sm:p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Year</label>
                        <input name="year" type="number" value="{{ old('year', $annualPlan->year) }}"
                            class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#121293]/30">
                        @error('year')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Title</label>
                        <input name="title" value="{{ old('title', $annualPlan->title) }}"
                            class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#121293]/30">
                        @error('title')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Description</label>
                        <textarea name="description" rows="4"
                            class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#121293]/30">{{ old('description', $annualPlan->description) }}</textarea>
                        @error('description')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <button
                            class="rounded-xl bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                            Update
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
