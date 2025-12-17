<x-app-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">{{ session('success') }}
                </div>
            @endif

            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="h-1 w-full bg-[#121293]"></div>

                <div class="p-5 sm:p-6 flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-xl font-semibold text-slate-900">Edit Course</h1>
                        <div class="mt-2"><x-status-badge :status="$course->status" label="Course" /></div>
                        <p class="text-sm text-slate-600 mt-2">
                            TOR: <b>{{ $course->torSubmission?->title ?: '—' }}</b>
                        </p>
                    </div>

                    <a href="{{ route('courses.index') }}"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Kembali
                    </a>
                </div>

                <form method="POST" action="{{ route('courses.update', $course) }}" class="p-5 sm:p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Course Type</label>
                        <select name="course_type_id"
                            class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                            <option value="">—</option>
                            @foreach ($types as $t)
                                <option value="{{ $t->id }}" @selected(old('course_type_id', $course->course_type_id) == $t->id)>
                                    {{ $t->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Title</label>
                        <input name="title" value="{{ old('title', $course->title) }}"
                            class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-700">Description</label>
                        <textarea name="description" rows="4" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">{{ old('description', $course->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Training Hours</label>
                            <input name="training_hours" type="number" step="0.01"
                                value="{{ old('training_hours', $course->training_hours) }}"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-slate-700">Status</label>
                            <select name="status"
                                class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                                <option value="draft" @selected(old('status', $course->status) === 'draft')>Draft</option>
                                <option value="published" @selected(old('status', $course->status) === 'published')>Published</option>
                                <option value="archived" @selected(old('status', $course->status) === 'archived')>Archived</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-2">
                        <form method="POST" action="{{ route('courses.destroy', $course) }}"
                            onsubmit="return confirm('Hapus course ini?');">
                            @csrf
                            @method('DELETE')
                            <button
                                class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                Hapus
                            </button>
                        </form>

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
