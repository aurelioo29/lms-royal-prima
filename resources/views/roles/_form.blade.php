@csrf

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-slate-700">
            Name <span class="text-red-500">*</span>
        </label>
        <input name="name" value="{{ old('name', $role->name ?? '') }}"
            class="mt-1 w-full rounded-lg border-slate-200 focus:border-slate-300 focus:ring-slate-200"
            placeholder="Contoh: Kabid Diklat" required>
        @error('name')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700">
            Slug <span class="text-red-500">*</span>
        </label>
        <input name="slug" value="{{ old('slug', $role->slug ?? '') }}"
            class="mt-1 w-full rounded-lg border-slate-200 focus:border-slate-300 focus:ring-slate-200"
            placeholder="contoh: head_training" required>
        <p class="text-xs text-slate-500 mt-1">Huruf kecil, angka, underscore.</p>
        @error('slug')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700">
            Level <span class="text-red-500">*</span>
        </label>
        <input type="number" name="level" value="{{ old('level', $role->level ?? 0) }}"
            class="mt-1 w-full rounded-lg border-slate-200 focus:border-slate-300 focus:ring-slate-200"
            placeholder="Contoh: 70" required>
        @error('level')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <label class="flex items-center gap-2">
        <input type="checkbox" name="can_manage_users" value="1"
            class="rounded border-slate-300 text-[#121293] focus:ring-[#121293]"
            {{ old('can_manage_users', $role->can_manage_users ?? false) ? 'checked' : '' }}>
        <span class="text-sm text-slate-700">Can Manage Users</span>
    </label>
</div>

<div class="mt-6 flex gap-2">
    <button class="px-4 py-2 rounded-lg bg-[#121293] text-white text-sm hover:opacity-90">
        Simpan
    </button>
    <a href="{{ route('roles.index') }}"
        class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-slate-50">
        Batal
    </a>
</div>
