<x-app-layout>
    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="h-1 w-full bg-[#121293]"></div>
                <div class="p-5 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <div class="min-w-0">
                            <h2 class="text-xl sm:text-2xl font-semibold text-slate-900 tracking-tight">
                                Edit TOR
                            </h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Status:
                                <span class="font-semibold text-slate-700">{{ strtoupper($tor->status) }}</span>
                                • Event:
                                <span class="font-semibold text-slate-700">{{ $tor->planEvent?->title ?? '—' }}</span>
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 justify-start lg:justify-end">
                            <a href="{{ route('annual-plans.events.edit', [$tor->planEvent->annualPlan, $tor->planEvent]) }}"
                                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Kembali ke Event
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-700">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            {{-- TOR FORM --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="p-5 sm:p-6">
                    <form method="POST" action="{{ route('tor-submissions.update', $tor) }}"
                        enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-6">
                                <div class="space-y-1">
                                    <label class="text-sm font-semibold text-slate-800">Judul TOR <span
                                            class="text-red-500">*</span></label>
                                    <input name="title" value="{{ old('title', $tor->title) }}" required
                                        class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 focus:border-[#121293] focus:ring-[#121293]">
                                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                                </div>

                                <div class="space-y-1">
                                    <label class="text-sm font-semibold text-slate-800">Konten</label>
                                    <textarea name="content" rows="10"
                                        class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 focus:border-[#121293] focus:ring-[#121293]">{{ old('content', $tor->content) }}</textarea>
                                    <x-input-error :messages="$errors->get('content')" class="mt-2" />
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                                    <div class="font-semibold text-slate-800 mb-1">Info</div>
                                    <div>Creator: <b>{{ $tor->creator?->name ?? '—' }}</b></div>
                                    <div>Reviewer: <b>{{ $tor->reviewer?->name ?? '—' }}</b></div>
                                    <div>Submitted:
                                        <b>{{ $tor->submitted_at ? $tor->submitted_at->format('d M Y H:i') : '—' }}</b>
                                    </div>
                                    <div>Reviewed:
                                        <b>{{ $tor->reviewed_at ? $tor->reviewed_at->format('d M Y H:i') : '—' }}</b>
                                    </div>
                                </div>

                                <div class="space-y-1">
                                    <label class="text-sm font-semibold text-slate-800">File TOR</label>
                                    @if ($tor->file_path)
                                        <a href="{{ asset('storage/' . $tor->file_path) }}" target="_blank"
                                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                            Lihat File
                                        </a>
                                        <div class="text-xs text-slate-500 mt-1">{{ $tor->file_path }}</div>
                                    @else
                                        <div class="text-sm text-slate-500">Belum ada file.</div>
                                    @endif
                                </div>

                                <div class="space-y-1">
                                    <label class="text-sm font-semibold text-slate-800">Ganti / Upload File
                                        (opsional)</label>
                                    <input type="file" name="file" accept=".pdf,.doc,.docx"
                                        class="block w-full text-sm text-slate-700
                                               file:mr-3 file:rounded-xl file:border-0 file:bg-[#121293]/10 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-[#121293] hover:file:bg-[#121293]/15">
                                    <x-input-error :messages="$errors->get('file')" class="mt-2" />
                                </div>

                                @if ($tor->review_notes)
                                    <div class="rounded-2xl border border-slate-200 bg-white p-4 text-sm">
                                        <div class="font-semibold text-slate-800 mb-1">Review Notes</div>
                                        <div class="text-slate-700 whitespace-pre-line">{{ $tor->review_notes }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pt-2">
                            <button
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#121293] px-4 py-2.5 text-sm font-semibold text-white hover:opacity-95">
                                Update TOR
                            </button>

                            {{-- SUBMIT --}}
                            @if (in_array($tor->status, ['draft', 'rejected'], true))
                                <form method="POST" action="{{ route('tor-submissions.submit', $tor) }}"
                                    onsubmit="return confirm('Ajukan TOR untuk ACC?')">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                        Submit untuk ACC
                                    </button>
                                </form>
                            @endif
                        </div>

                    </form>
                </div>
            </div>

            {{-- APPROVAL PANEL (untuk role approve) --}}
            @if ($tor->status === 'submitted')
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 p-5 sm:p-6">
                        <div class="text-sm font-semibold text-slate-800">Keputusan Reviewer</div>
                        <div class="text-sm text-slate-500 mt-1">Approve atau reject TOR ini.</div>
                    </div>

                    <div class="p-5 sm:p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- APPROVE --}}
                        <form method="POST" action="{{ route('tor-submissions.approve', $tor) }}" class="space-y-3">
                            @csrf
                            @method('PATCH')

                            <label class="text-sm font-semibold text-slate-800">Review Notes (opsional)</label>
                            <textarea name="review_notes" rows="4"
                                class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 focus:border-[#121293] focus:ring-[#121293]"
                                placeholder="Catatan saat approve...">{{ old('review_notes') }}</textarea>

                            <button
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-green-600 px-4 py-2.5 text-sm font-semibold text-white hover:opacity-95">
                                Approve
                            </button>
                        </form>

                        {{-- REJECT --}}
                        <form method="POST" action="{{ route('tor-submissions.reject', $tor) }}" class="space-y-3">
                            @csrf
                            @method('PATCH')

                            <label class="text-sm font-semibold text-slate-800">Review Notes <span
                                    class="text-red-500">*</span></label>
                            <textarea name="review_notes" rows="4" required
                                class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 focus:border-[#121293] focus:ring-[#121293]"
                                placeholder="Alasan reject...">{{ old('review_notes') }}</textarea>

                            <button
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:opacity-95">
                                Reject
                            </button>
                        </form>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
