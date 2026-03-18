@csrf

<div class="grid grid-cols-1 gap-5 md:grid-cols-2">
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Nama</label>
        <input type="text" name="name" value="{{ old('name', $account->name ?? '') }}"
            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#121293] focus:ring-[#121293]/20">
        @error('name')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
        <input type="email" name="email" value="{{ old('email', $account->email ?? '') }}"
            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#121293] focus:ring-[#121293]/20">
        @error('email')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">NIK</label>
        <input type="text" name="nik" value="{{ old('nik', $account->nik ?? '') }}"
            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#121293] focus:ring-[#121293]/20">
        @error('nik')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Phone</label>
        <input type="text" name="phone" value="{{ old('phone', $account->phone ?? '') }}"
            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#121293] focus:ring-[#121293]/20">
        @error('phone')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Birth Date</label>
        <input type="date" name="birth_date"
            value="{{ old('birth_date', isset($account) && $account->birth_date ? $account->birth_date->format('Y-m-d') : '') }}"
            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#121293] focus:ring-[#121293]/20">
        @error('birth_date')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Gender</label>
        <select name="gender"
            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#121293] focus:ring-[#121293]/20">
            <option value="">Pilih Gender</option>
            <option value="M" @selected(old('gender', $account->gender ?? '') === 'M')>Male</option>
            <option value="F" @selected(old('gender', $account->gender ?? '') === 'F')>Female</option>
        </select>
        @error('gender')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Role</label>
        <select name="role_id"
            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#121293] focus:ring-[#121293]/20">
            <option value="">Pilih Role</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}" @selected(old('role_id', $account->role_id ?? '') == $role->id)>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
        @error('role_id')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Job Category</label>
        <select name="job_category_id"
            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#121293] focus:ring-[#121293]/20">
            <option value="">Pilih Job Category</option>
            @foreach ($jobCategories as $item)
                <option value="{{ $item->id }}" @selected(old('job_category_id', $account->job_category_id ?? '') == $item->id)>
                    {{ $item->name }}
                </option>
            @endforeach
        </select>
        @error('job_category_id')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Job Title</label>
        <select name="job_title_id"
            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#121293] focus:ring-[#121293]/20">
            <option value="">Pilih Job Title</option>
            @foreach ($jobTitles as $item)
                <option value="{{ $item->id }}" @selected(old('job_title_id', $account->job_title_id ?? '') == $item->id)>
                    {{ $item->name }}
                </option>
            @endforeach
        </select>
        @error('job_title_id')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Jabatan</label>
        <input type="text" name="jabatan" value="{{ old('jabatan', $account->jabatan ?? '') }}"
            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#121293] focus:ring-[#121293]/20">
        @error('jabatan')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Unit</label>
        <input type="text" name="unit" value="{{ old('unit', $account->unit ?? '') }}"
            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#121293] focus:ring-[#121293]/20">
        @error('unit')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">
            Password {{ isset($account) ? '(optional)' : '(optional, auto-generate if empty)' }}
        </label>
        <input type="text" name="password" value="{{ old('password') }}"
            placeholder="Leave empty to use generic password"
            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#121293] focus:ring-[#121293]/20">
        @error('password')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">
            Confirm Password
        </label>
        <input type="text" name="password_confirmation" value="{{ old('password_confirmation') }}"
            placeholder="Only needed if password is filled"
            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#121293] focus:ring-[#121293]/20">
    </div>

    <div class="md:col-span-2">
        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $account->is_active ?? true))>
            <span>Akun aktif</span>
        </label>
    </div>
</div>

<div class="flex items-center gap-3 pt-2">
    <button type="submit"
        class="inline-flex items-center justify-center rounded-xl bg-[#121293] px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90">
        Simpan
    </button>

    <a href="{{ route('accounts.index') }}"
        class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
        Batal
    </a>
</div>
