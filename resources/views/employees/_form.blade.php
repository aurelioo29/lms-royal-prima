@php
    $employee = $employee ?? null;
    $isEdit = !is_null($employee);
@endphp

<div class="space-y-4">

    <div>
        <x-required-label value="Nama Lengkap" />
        <x-text-input name="name" class="mt-1 w-full" value="{{ old('name', $employee->name ?? '') }}" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <x-required-label value="NIK" />
            <x-text-input name="nik" class="mt-1 w-full" value="{{ old('nik', $employee->nik ?? '') }}" required />
            <x-input-error :messages="$errors->get('nik')" class="mt-2" />
        </div>

        <div>
            <x-required-label value="No Handphone" />
            <x-text-input name="phone" class="mt-1 w-full" value="{{ old('phone', $employee->phone ?? '') }}"
                required />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>
    </div>

    <div>
        <x-required-label value="Email" />
        <x-text-input type="email" name="email" class="mt-1 w-full"
            value="{{ old('email', $employee->email ?? '') }}" required />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <x-required-label value="Tanggal Lahir" />
            <x-text-input type="date" name="birth_date" class="mt-1 w-full"
                value="{{ old('birth_date', optional($employee?->birth_date)->format('Y-m-d')) }}" required />
            <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
        </div>

        <div>
            <x-required-label value="Jenis Kelamin" />
            <div class="mt-2 flex items-center gap-6">
                @php $gender = old('gender', $employee->gender ?? 'M'); @endphp

                <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                    <input type="radio" name="gender" value="M" {{ $gender === 'M' ? 'checked' : '' }} required>
                    Laki-laki
                </label>

                <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                    <input type="radio" name="gender" value="F" {{ $gender === 'F' ? 'checked' : '' }} required>
                    Perempuan
                </label>
            </div>
            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <x-required-label value="Job Category" />
            <select name="job_category_id"
                class="mt-1 w-full rounded-lg border-slate-200 focus:border-[#121293] focus:ring-[#121293]" required>
                <option value="">-- pilih --</option>
                @foreach ($jobCategories as $jc)
                    <option value="{{ $jc->id }}" @selected(old('job_category_id', $employee->job_category_id ?? '') == $jc->id)>
                        {{ $jc->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('job_category_id')" class="mt-2" />
        </div>

        <div>
            <x-required-label value="Job Title" />
            <select name="job_title_id"
                class="mt-1 w-full rounded-lg border-slate-200 focus:border-[#121293] focus:ring-[#121293]" required>
                <option value="">-- pilih --</option>
                @foreach ($jobTitles as $jt)
                    <option value="{{ $jt->id }}" @selected(old('job_title_id', $employee->job_title_id ?? '') == $jt->id)>
                        {{ $jt->name }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('job_title_id')" class="mt-2" />
        </div>
    </div>

    <div>
        <x-required-label value="Status" />
        <select name="is_active"
            class="mt-1 w-full rounded-lg border-slate-200 focus:border-[#121293] focus:ring-[#121293]" required>
            <option value="1" @selected(old('is_active', (string) ($employee->is_active ?? 1)) === '1')>Active</option>
            <option value="0" @selected(old('is_active', (string) ($employee->is_active ?? 1)) === '0')>Inactive</option>
        </select>
        <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
    </div>

    {{-- Password block (Generate + Copy + Show/Hide) --}}
    <div x-data="{
        showPass: false,
        showConfirm: false,
        pass: @js(old('password', '')),
        confirm: @js(old('password_confirmation', '')),
        generate(len = 12) {
            const upper = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
            const lower = 'abcdefghijkmnpqrstuvwxyz';
            const nums = '23456789';
            const sym = '!@#$%^&*_-+=?';
            const all = upper + lower + nums + sym;
            const pick = (s) => s[Math.floor(Math.random() * s.length)];
            let pwd = [pick(upper), pick(lower), pick(nums), pick(sym)];
            for (let i = pwd.length; i < len; i++) pwd.push(pick(all));
            pwd = pwd.sort(() => Math.random() - 0.5).join('');
            this.pass = pwd;
            this.confirm = pwd;
        },
        async copy() {
            try { await navigator.clipboard.writeText(this.pass || ''); } catch (e) { alert('Gagal copy password.'); }
        }
    }" class="grid md:grid-cols-2 gap-4">
        <div>
            <x-required-label value="Password" />

            <div class="mt-1 relative">
                <input :type="showPass ? 'text' : 'password'" name="password"
                    class="w-full rounded-lg border-slate-200 focus:border-[#121293] focus:ring-[#121293] pr-28"
                    x-model="pass" {{ $isEdit ? '' : 'required' }} autocomplete="new-password" />

                <div class="absolute inset-y-0 right-2 flex items-center gap-1">
                    <button type="button" @click="showPass = !showPass"
                        class="px-2 py-1 text-xs rounded-md border border-slate-200 hover:bg-slate-50">
                        <span x-text="showPass ? 'Hide' : 'Show'"></span>
                    </button>

                    <button type="button" @click="generate(12)"
                        class="px-2 py-1 text-xs rounded-md bg-[#121293] text-white hover:opacity-90">
                        Gen
                    </button>

                    <button type="button" @click="copy()"
                        class="px-2 py-1 text-xs rounded-md border border-slate-200 hover:bg-slate-50"
                        :disabled="!pass" :class="!pass ? 'opacity-50 cursor-not-allowed' : ''">
                        Copy
                    </button>
                </div>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />

            @if ($isEdit)
                <p class="text-xs text-slate-500 mt-1">Kosongkan jika tidak ingin mengganti password.</p>
            @endif
        </div>

        <div>
            <x-required-label value="Konfirmasi Password" />

            <div class="mt-1 relative">
                <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation"
                    class="w-full rounded-lg border-slate-200 focus:border-[#121293] focus:ring-[#121293] pr-20"
                    x-model="confirm" {{ $isEdit ? '' : 'required' }} autocomplete="new-password" />

                <div class="absolute inset-y-0 right-2 flex items-center">
                    <button type="button" @click="showConfirm = !showConfirm"
                        class="px-2 py-1 text-xs rounded-md border border-slate-200 hover:bg-slate-50">
                        <span x-text="showConfirm ? 'Hide' : 'Show'"></span>
                    </button>
                </div>
            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
    </div>

    <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3 pt-2">
        <button class="px-4 py-2 rounded-lg bg-[#121293] text-white hover:opacity-90">
            {{ $submitText ?? 'Simpan' }}
        </button>

        <a href="{{ route('employees.index') }}"
            class="px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 text-center">
            Batal
        </a>
    </div>

</div>
