<x-app-layout>
    <div class="py-6">
        <div class="max-w-full px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Alerts --}}
            @if (session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">
                    <div class="flex items-start gap-3">
                        <svg class="h-5 w-5 mt-0.5" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="currentColor"
                                d="M12 2a10 10 0 1 0 0 20a10 10 0 0 0 0-20Zm-1 14l-4-4l1.4-1.4L11 13.2l5.6-5.6L18 9l-7 7Z" />
                        </svg>
                        <div class="text-sm font-semibold leading-relaxed">{{ session('success') }}</div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
                    <div class="flex items-start gap-3">
                        <svg class="h-5 w-5 mt-0.5" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="currentColor"
                                d="M12 2a10 10 0 1 0 0 20a10 10 0 0 0 0-20Zm1 13h-2v-2h2v2Zm0-4h-2V7h2v4Z" />
                        </svg>
                        <div class="text-sm font-semibold leading-relaxed">{{ session('error') }}</div>
                    </div>
                </div>
            @endif

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="h-1 w-full bg-[#121293]"></div>

                {{-- Header --}}
                <div class="p-5 sm:p-6 space-y-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <h1 class="text-lg sm:text-xl font-semibold text-slate-900">TOR</h1>

                            <div class="mt-2 flex flex-wrap gap-2">
                                <x-status-badge :status="$tor->status" label="TOR" />
                                <x-status-badge :status="optional($tor->planEvent)->status ?? 'unknown'" label="Event" />
                                <x-status-badge :status="optional(optional($tor->planEvent)->annualPlan)->status ?? 'unknown'" label="Plan" />
                            </div>

                            <div class="mt-3 rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm">
                                <div class="text-slate-500">Event</div>
                                <div class="font-semibold text-slate-900">
                                    {{ optional($tor->planEvent)->title ?? '—' }}
                                </div>
                            </div>
                        </div>

                        <a href="{{ $tor->planEvent ? route('annual-plans.show', $tor->planEvent->annual_plan_id) : route('annual-plans.index') }}"
                            class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Kembali
                        </a>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-wrap items-center gap-2">

                        {{-- Direktur approve/reject --}}
                        @if (auth()->user()->canApproveTOR() && $tor->status === 'submitted')
                            <form method="POST"
                                action="{{ route('tor-submissions.approve', ['torSubmission' => $tor->id]) }}"
                                class="flex flex-wrap items-center gap-2">
                                @csrf
                                @method('PATCH')

                                <input name="review_notes" placeholder="Catatan (opsional)"
                                    class="w-full sm:w-72 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#121293]/20" />

                                <button
                                    class="inline-flex items-center rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                    Approve
                                </button>
                            </form>

                            <form method="POST"
                                action="{{ route('tor-submissions.reject', ['torSubmission' => $tor->id]) }}"
                                class="flex flex-wrap items-center gap-2">
                                @csrf
                                @method('PATCH')

                                <input name="review_notes" placeholder="Alasan (opsional)"
                                    class="w-full sm:w-72 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500/10" />

                                <button
                                    class="inline-flex items-center rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-100">
                                    Reject
                                </button>
                            </form>
                        @endif

                        {{-- Admin buat course --}}
                        @if (auth()->user()->canCreateCourses() && $tor->status === 'approved')
                            <a href="{{ route('courses.create', ['tor_submission_id' => $tor->id]) }}"
                                class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                Buat Course
                            </a>
                        @endif
                    </div>

                    {{-- Notes --}}
                    @if ($tor->review_notes)
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                            <div class="font-semibold text-slate-900">Catatan Reviewer</div>
                            <div class="mt-1 whitespace-pre-line">{{ $tor->review_notes }}</div>
                        </div>
                    @endif
                </div>

                <div class="border-t border-slate-200"></div>

                {{-- Form --}}
                <form method="POST" action="{{ route('tor-submissions.update', ['torSubmission' => $tor->id]) }}"
                    enctype="multipart/form-data" class="p-5 sm:p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Title</label>
                            <input name="title" value="{{ old('title', $tor->title) }}"
                                class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#121293]/20" />
                            @error('title')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-slate-700">Content</label>
                            <textarea name="content" rows="7"
                                class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#121293]/20">{{ old('content', $tor->content) }}</textarea>
                            @error('content')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="text-sm font-semibold text-slate-700">Upload File Baru (opsional)</label>
                                <input type="file" name="file"
                                    class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm" />
                                @error('file')
                                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm">
                                <div class="font-semibold text-slate-900">File Saat Ini</div>
                                @if ($tor->file_path)
                                    <a href="{{ asset('storage/' . $tor->file_path) }}" target="_blank"
                                        class="mt-1 inline-flex items-center gap-2 text-[#121293] font-semibold hover:underline">
                                        <span>Lihat File</span>
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" aria-hidden="true">
                                            <path fill="currentColor"
                                                d="M14 3h7v7h-2V6.41l-9.29 9.3l-1.42-1.42l9.3-9.29H14V3ZM5 5h6v2H7v10h10v-4h2v6H5V5Z" />
                                        </svg>
                                    </a>
                                @else
                                    <div class="mt-1 text-slate-500">—</div>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button
                                class="rounded-xl bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                Update TOR
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
