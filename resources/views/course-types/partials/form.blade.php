@php
    $t = $courseType ?? new \App\Models\CourseType();
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="space-y-6">
        <div class="space-y-1">
            <label class="text-sm font-semibold text-slate-800">Name <span class="text-red-500">*</span></label>
            <input name="name" value="{{ old('name', $t->name) }}" required
                class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 focus:border-[#121293] focus:ring-[#121293]"
                placeholder="Contoh: In-House Training">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="space-y-1">
            <label class="text-sm font-semibold text-slate-800">Slug <span class="text-red-500">*</span></label>
            <input name="slug" value="{{ old('slug', $t->slug) }}" required
                class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 focus:border-[#121293] focus:ring-[#121293]"
                placeholder="contoh: in-house-training">
            <x-input-error :messages="$errors->get('slug')" class="mt-2" />
            <p class="text-xs text-slate-500">Slug wajib unik. Jangan pake spasi, jangan drama.</p>
        </div>
    </div>

    <div class="space-y-6">
        <div class="space-y-1">
            <label class="text-sm font-semibold text-slate-800">Description</label>
            <textarea name="description" rows="6"
                class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 focus:border-[#121293] focus:ring-[#121293]"
                placeholder="Penjelasan singkat...">{{ old('description', $t->description) }}</textarea>
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>

        <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-800">
            <input type="checkbox" name="is_active" value="1"
                class="rounded border-slate-300 text-[#121293] focus:ring-[#121293]" @checked(old('is_active', $t->is_active ?? true))>
            Active
        </label>
        <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
    </div>
</div>

<div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pt-2">
    <a href="{{ route('course-types.index') }}"
        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
        Batal
    </a>

    <button
        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#121293] px-4 py-2.5 text-sm font-semibold text-white hover:opacity-95">
        {{ $submitText ?? 'Simpan' }}
    </button>
</div>
