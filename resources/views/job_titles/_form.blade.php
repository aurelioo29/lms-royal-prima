@csrf
<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-slate-700">
            Category <span class="text-red-500">*</span>
        </label>
        <select name="job_category_id"
            class="mt-1 w-full rounded-lg border-slate-200 focus:border-slate-300 focus:ring-slate-200" required>
            <option value="">-- pilih kategori --</option>
            @foreach ($categories as $c)
                <option value="{{ $c->id }}"
                    {{ (int) old('job_category_id', $title->job_category_id ?? 0) === $c->id ? 'selected' : '' }}>
                    {{ $c->name }}
                </option>
            @endforeach
        </select>
        @error('job_category_id')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700">
            Name <span class="text-red-500">*</span>
        </label>
        <input name="name" value="{{ old('name', $title->name ?? '') }}"
            class="mt-1 w-full rounded-lg border-slate-200 focus:border-slate-300 focus:ring-slate-200" required
            placeholder="Contoh: Dokter Spesialis">
        @error('name')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700">
            Slug <span class="text-red-500">*</span>
        </label>
        <input name="slug" value="{{ old('slug', $title->slug ?? '') }}"
            class="mt-1 w-full rounded-lg border-slate-200 focus:border-slate-300 focus:ring-slate-200" required
            placeholder="contoh: dokter_spesialis">
        @error('slug')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <label class="flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1"
            class="rounded border-slate-300 text-[#121293] focus:ring-[#121293]"
            {{ old('is_active', $title->is_active ?? true) ? 'checked' : '' }}>
        <span class="text-sm text-slate-700">Active</span>
    </label>
</div>

<div class="mt-6 flex gap-2">
    <button class="px-4 py-2 rounded-lg bg-[#121293] text-white text-sm hover:opacity-90">Simpan</button>
    <a href="{{ route('job-titles.index') }}"
        class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-slate-50">Batal</a>
</div>
