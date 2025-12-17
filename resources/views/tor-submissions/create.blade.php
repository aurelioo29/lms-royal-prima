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
                <div class="h-1 w-full bg-[#121293]"></div>

                @php
                    $eventStatus = $planEvent->status ?? 'draft';
                    $eventChip = match ($eventStatus) {
                        'draft' => 'bg-slate-50 text-slate-700 border-slate-200',
                        'pending' => 'bg-amber-50 text-amber-800 border-amber-200',
                        'approved' => 'bg-green-50 text-green-800 border-green-200',
                        'rejected' => 'bg-red-50 text-red-800 border-red-200',
                        default => 'bg-slate-50 text-slate-700 border-slate-200',
                    };
                @endphp

                {{-- Header --}}
                <div class="px-5 py-4 sm:px-6 border-b border-slate-200">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <h1 class="text-lg sm:text-xl font-semibold text-slate-900">Buat TOR</h1>

                            <div class="mt-2 flex flex-wrap items-center gap-2 text-sm">
                                <span class="text-slate-600">
                                    Event: <span class="font-semibold text-slate-900">{{ $planEvent->title }}</span>
                                </span>
                                <span
                                    class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $eventChip }}">
                                    {{ strtoupper($eventStatus) }}
                                </span>
                            </div>
                        </div>

                        <a href="{{ route('annual-plans.show', $planEvent->annual_plan_id) }}"
                            class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Kembali
                        </a>
                    </div>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('tor-submissions.store') }}" enctype="multipart/form-data"
                    class="px-5 py-5 sm:px-6 space-y-5">
                    @csrf

                    <input type="hidden" name="plan_event_id" value="{{ $planEvent->id }}">

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Judul</label>
                        <input name="title" value="{{ old('title') }}"
                            class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900
                                   placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#121293]/20"
                            placeholder="Judul TOR">
                        @error('title')
                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Konten</label>
                        <textarea name="content" rows="7"
                            class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900
                                   placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#121293]/20"
                            placeholder="Isi konten TOR">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">File (PDF/DOC/DOCX)</label>

                        <div class="mt-1 rounded-lg border border-slate-200 bg-white p-3">
                            <input type="file" name="file"
                                accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                class="block w-full text-sm text-slate-700
                                       file:mr-4 file:rounded-lg file:border-0 file:bg-slate-900 file:px-4 file:py-2
                                       file:text-sm file:font-semibold file:text-white hover:file:opacity-90" />
                        </div>

                        @error('file')
                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-2">
                        <a href="{{ route('annual-plans.show', $planEvent->annual_plan_id) }}"
                            class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Batal
                        </a>
                        <button
                            class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
