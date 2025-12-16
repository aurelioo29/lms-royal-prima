@php
    $c = $course ?? new \App\Models\Course();
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <div class="space-y-6">
        {{-- TOR (read-only) --}}
        <div class="space-y-1">
            <label class="text-sm font-semibold text-slate-800">TOR</label>
            <input value="{{ $tor?->title ?? ($c->torSubmission?->title ?? '—') }}" disabled
                class="w-full rounded-xl border-slate-200 bg-slate-50 px-3 py-2.5 text-slate-700">
            <input type="hidden" name="tor_submission_id"
                value="{{ old('tor_submission_id', $tor?->id ?? $c->tor_submission_id) }}">
            <x-input-error :messages="$errors->get('tor_submission_id')" class="mt-2" />
            <p class="text-xs text-slate-500">Course hanya dibuat dari TOR yang sudah <b>approved</b>.</p>
        </div>

        {{-- Title --}}
        <div class="space-y-1">
            <label class="text-sm font-semibold text-slate-800">Judul Course <span class="text-red-500">*</span></label>
            <input name="title" value="{{ old('title', $c->title) }}" required
                class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5
                       focus:border-[#121293] focus:ring-[#121293]"
                placeholder="Contoh: Pelatihan K3 Dasar">
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
        </div>

        {{-- Description --}}
        <div class="space-y-1">
            <label class="text-sm font-semibold text-slate-800">Deskripsi</label>
            <textarea name="description" rows="6"
                class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5
                       focus:border-[#121293] focus:ring-[#121293]"
                placeholder="Ringkasan course, tujuan, materi...">{{ old('description', $c->description) }}</textarea>
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>
    </div>

    <div class="space-y-6">

        {{-- Type --}}
        <div class="space-y-1">
            <label class="text-sm font-semibold text-slate-800">Course Type <span class="text-red-500">*</span></label>
            <select name="course_type_id" required
                class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5
                       focus:border-[#121293] focus:ring-[#121293]">
                <option value="">— pilih type —</option>
                @foreach ($types as $t)
                    <option value="{{ $t->id }}" @selected(old('course_type_id', $c->course_type_id) == $t->id)>
                        {{ $t->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('course_type_id')" class="mt-2" />
        </div>

        {{-- Training Hours --}}
        <div class="space-y-1">
            <label class="text-sm font-semibold text-slate-800">Training Hours (jam) <span
                    class="text-red-500">*</span></label>
            <input type="number" min="0" step="0.5" name="training_hours"
                value="{{ old('training_hours', $c->training_hours) }}" required
                class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5
                       focus:border-[#121293] focus:ring-[#121293]"
                placeholder="Contoh: 8">
            <x-input-error :messages="$errors->get('training_hours')" class="mt-2" />
        </div>

        {{-- Status --}}
        <div class="space-y-1">
            <label class="text-sm font-semibold text-slate-800">Status <span class="text-red-500">*</span></label>
            @php $st = old('status', $c->status ?? 'draft'); @endphp
            <select name="status" required
                class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5
                       focus:border-[#121293] focus:ring-[#121293]">
                <option value="draft" @selected($st === 'draft')>Draft</option>
                <option value="published" @selected($st === 'published')>Published</option>
                <option value="archived" @selected($st === 'archived')>Archived</option>
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>

        {{-- Info --}}
        @if ($c->exists)
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
                <div class="font-semibold text-slate-800 mb-1">Info</div>
                <div>Type: <b>{{ $c->type?->name ?? '—' }}</b></div>
                <div>TOR Event: <b>{{ $c->torSubmission?->planEvent?->title ?? '—' }}</b></div>
            </div>
        @endif

    </div>
</div>

<div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pt-2">
    <a href="{{ route('courses.index') }}"
        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
        Batal
    </a>

    <button
        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#121293] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-[#121293] focus:ring-offset-2">
        {{ $submitText ?? 'Simpan' }}
    </button>
</div>
