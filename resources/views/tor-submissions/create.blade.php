<x-app-layout>
    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="h-1 w-full bg-[#121293]"></div>
                <div class="p-5 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <div>
                            <h2 class="text-xl sm:text-2xl font-semibold text-slate-900 tracking-tight">
                                Buat TOR (Draft)
                            </h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Untuk event: <span class="font-semibold text-slate-700">{{ $event->title }}</span>
                            </p>
                        </div>

                        <a href="{{ route('annual-plans.show', $event->annualPlan) }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            @if (session('error'))
                <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="p-5 sm:p-6">
                    <form method="POST" action="{{ route('tor-submissions.store') }}" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        <input type="hidden" name="plan_event_id" value="{{ $event->id }}">

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-6">
                                <div class="space-y-1">
                                    <label class="text-sm font-semibold text-slate-800">Judul TOR <span
                                            class="text-red-500">*</span></label>
                                    <input name="title" value="{{ old('title') }}" required
                                        class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 focus:border-[#121293] focus:ring-[#121293]"
                                        placeholder="Contoh: TOR Pelatihan K3 Dasar">
                                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                                </div>

                                <div class="space-y-1">
                                    <label class="text-sm font-semibold text-slate-800">Konten</label>
                                    <textarea name="content" rows="10"
                                        class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 focus:border-[#121293] focus:ring-[#121293]"
                                        placeholder="Isi TOR...">{{ old('content') }}</textarea>
                                    <x-input-error :messages="$errors->get('content')" class="mt-2" />
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                                    <div class="font-semibold text-slate-800 mb-1">Info Event</div>
                                    <div>Judul: <b>{{ $event->title }}</b></div>
                                    <div>Tanggal: <b>{{ optional($event->date)->format('d M Y') ?? $event->date }}</b>
                                    </div>
                                    <div>Target: <b>{{ $event->target_audience ?? '—' }}</b></div>
                                </div>

                                <div class="space-y-1">
                                    <label class="text-sm font-semibold text-slate-800">Upload File (opsional)</label>
                                    <input type="file" name="file" accept=".pdf,.doc,.docx"
                                        class="block w-full text-sm text-slate-700
                                               file:mr-3 file:rounded-xl file:border-0 file:bg-[#121293]/10 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-[#121293] hover:file:bg-[#121293]/15">
                                    <p class="text-xs text-slate-500">Maks 10MB. PDF/DOC/DOCX.</p>
                                    <x-input-error :messages="$errors->get('file')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pt-2">
                            <a href="{{ route('annual-plans.events.edit', [$event->annualPlan, $event]) }}"
                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Batal
                            </a>

                            <button
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#121293] px-4 py-2.5 text-sm font-semibold text-white hover:opacity-95">
                                Simpan Draft TOR
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
