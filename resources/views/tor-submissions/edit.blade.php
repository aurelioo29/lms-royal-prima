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

                <div class="p-5 sm:p-6 space-y-4">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h1 class="text-xl font-semibold text-slate-900">TOR</h1>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <x-status-badge :status="$tor->status" label="TOR" />
                                <x-status-badge :status="optional($tor->planEvent)->status ?? 'unknown'" label="Event" />
                                <x-status-badge :status="optional(optional($tor->planEvent)->annualPlan)->status ?? 'unknown'" label="Plan" />
                            </div>
                            <p class="text-sm text-slate-600 mt-2">
                                Event:
                                <b>{{ optional($tor->planEvent)->title ?? '-' }}</b>
                            </p>
                        </div>

                        <a href="{{ $tor->planEvent ? route('annual-plans.show', $tor->planEvent->annual_plan_id) : route('annual-plans.index') }}"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Kembali
                        </a>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        {{-- Kabid submit --}}
                        @if (auth()->user()->canCreateTOR() && in_array($tor->status, ['draft', 'rejected'], true))
                            <form method="POST"
                                action="{{ route('tor-submissions.submit', ['torSubmission' => $tor->id]) }}">
                                @csrf
                                @method('PATCH')
                                <button
                                    class="rounded-xl bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                    Ajukan ACC TOR
                                </button>
                            </form>
                        @endif

                        {{-- Direktur approve/reject --}}
                        @if (auth()->user()->canApproveTOR())
                            @if ($tor->status === 'submitted')
                                <form method="POST"
                                    action="{{ route('tor-submissions.approve', ['torSubmission' => $tor->id]) }}"
                                    class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input name="review_notes" placeholder="Catatan (opsional)"
                                        class="w-56 rounded-xl border border-slate-200 px-3 py-2 text-sm" />
                                    <button
                                        class="rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                        Approve TOR
                                    </button>
                                </form>

                                <form method="POST"
                                    action="{{ route('tor-submissions.reject', ['torSubmission' => $tor->id]) }}"
                                    class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input name="review_notes" placeholder="Alasan (opsional)"
                                        class="w-56 rounded-xl border border-slate-200 px-3 py-2 text-sm" />
                                    <button
                                        class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                        Reject TOR
                                    </button>
                                </form>
                            @endif
                        @endif

                        {{-- Admin buat course --}}
                        @if (auth()->user()->canCreateCourses() && $tor->status === 'approved')
                            <a href="{{ route('courses.create', ['tor_submission_id' => $tor->id]) }}"
                                class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                Buat Course
                            </a>
                        @endif
                    </div>

                    @if ($tor->review_notes)
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm text-slate-700">
                            <div class="font-semibold">Catatan Reviewer:</div>
                            <div>{{ $tor->review_notes }}</div>
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('tor-submissions.update', ['torSubmission' => $tor->id]) }}"
                    enctype="multipart/form-data" class="p-5 sm:p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Title</label>
                        <input name="title" value="{{ old('title', $tor->title) }}"
                            class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Content</label>
                        <textarea name="content" rows="6" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">{{ old('content', $tor->content) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Upload File Baru (opsional)</label>
                            <input type="file" name="file"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm bg-white">
                        </div>

                        <div class="text-sm text-slate-700">
                            <div class="font-semibold">File Saat Ini</div>
                            @if ($tor->file_path)
                                <a href="{{ asset('storage/' . $tor->file_path) }}" target="_blank"
                                    class="text-[#121293] underline">
                                    Lihat file
                                </a>
                            @else
                                <div class="text-slate-500">—</div>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <button
                            class="rounded-xl bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                            Update TOR
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
