<x-app-layout>
    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- HERO --}}
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="h-1 w-full bg-[#121293]"></div>

                <div class="p-5 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex items-start gap-3">
                                <div
                                    class="mt-1 inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-[#121293]/10 text-[#121293]">
                                    {{-- edit icon --}}
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M12 20h9" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" />
                                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>

                                <div class="min-w-0">
                                    <h2 class="text-xl sm:text-2xl font-semibold text-slate-900 tracking-tight">
                                        Edit Event
                                    </h2>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Plan: <span class="font-semibold text-slate-700">{{ $annualPlan->title }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 justify-start lg:justify-end">
                            <a href="{{ route('annual-plans.show', $annualPlan) }}"
                                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FORM CARD --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 p-5 sm:p-6">
                    <div class="text-sm text-slate-500">
                        Update detail event. Jangan bikin jadwal “ngambang”—isi jam kalau perlu 😄
                    </div>
                </div>

                <div class="p-5 sm:p-6">
                    <form method="POST" action="{{ route('annual-plans.events.update', [$annualPlan, $planEvent]) }}"
                        class="space-y-6">
                        @csrf
                        @method('PUT')

                        @include('plan-events.partials.form', [
                            'planEvent' => $planEvent,
                            'submitText' => 'Update',
                            'annualPlan' => $annualPlan,
                        ])
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
