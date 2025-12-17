<x-app-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="h-1 w-full bg-[#121293]"></div>

                <div class="p-5 sm:p-6">
                    <h1 class="text-xl font-semibold text-slate-900">Buat Course</h1>
                    @if ($tor)
                        <p class="text-sm text-slate-600 mt-1">
                            Dari TOR: <b>{{ $tor->title }}</b> • Status TOR:
                            <span class="font-semibold">{{ strtoupper($tor->status) }}</span>
                        </p>
                    @endif
                </div>

                <form method="POST" action="{{ route('courses.store') }}" class="p-5 sm:p-6 space-y-4">
                    @csrf

                    <input type="hidden" name="tor_submission_id" value="{{ old('tor_submission_id', $tor?->id) }}">

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Course Type</label>
                        <select name="course_type_id"
                            class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                            <option value="">—</option>
                            @foreach ($types as $t)
                                <option value="{{ $t->id }}" @selected(old('course_type_id') == $t->id)>{{ $t->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_type_id')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

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
                            <label class="text-sm font-semibold text-slate-700">Training Hours</label>
                            <input name="training_hours" type="number" step="0.01"
                                value="{{ old('training_hours', 0) }}"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                            @error('training_hours')
                                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-slate-700">Status</label>
                            <select name="status"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                                <option value="draft" @selected(old('status', 'draft') === 'draft')>Draft</option>
                                <option value="published" @selected(old('status') === 'published')>Published</option>
                                <option value="archived" @selected(old('status') === 'archived')>Archived</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('courses.index') }}"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Batal
                        </a>
                        <button
                            class="rounded-xl bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                            Buat Course
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
