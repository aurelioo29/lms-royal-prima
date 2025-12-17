<x-app-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="h-1 w-full bg-[#121293]"></div>
                <div class="p-5 sm:p-6">
                    <h1 class="text-xl font-semibold text-slate-900">Buat TOR</h1>
                    <p class="text-sm text-slate-600 mt-1">
                        Event: <b>{{ $event->title }}</b> • Status Event: <span
                            class="font-semibold">{{ strtoupper($event->status) }}</span>
                    </p>
                </div>

                <form method="POST" action="{{ route('tor-submissions.store') }}" enctype="multipart/form-data"
                    class="p-5 sm:p-6 space-y-4">
                    @csrf

                    <input type="hidden" name="plan_event_id" value="{{ $event->id }}">

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Title</label>
                        <input name="title" value="{{ old('title') }}"
                            class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        @error('title')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Content</label>
                        <textarea name="content" rows="6" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">{{ old('content') }}</textarea>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">File (PDF/DOC/DOCX)</label>
                        <input type="file" name="file"
                            class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm bg-white">
                        @error('file')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('annual-plans.show', $event->annual_plan_id) }}"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Batal
                        </a>
                        <button
                            class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                            Simpan TOR (Draft)
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
