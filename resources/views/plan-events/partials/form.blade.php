@php
    $e = $planEvent;
@endphp

<div>
    <label class="text-sm font-medium text-slate-700">Judul <span class="text-red-500">*</span></label>
    <input name="title" value="{{ old('title', $e->title ?? '') }}"
        class="mt-1 w-full rounded-lg border-slate-200 focus:border-[#121293] focus:ring-[#121293]" required>
    <x-input-error :messages="$errors->get('title')" class="mt-2" />
</div>

<div>
    <label class="text-sm font-medium text-slate-700">Deskripsi</label>
    <textarea name="description" rows="3"
        class="mt-1 w-full rounded-lg border-slate-200 focus:border-[#121293] focus:ring-[#121293]">{{ old('description', $e->description ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

<div class="grid md:grid-cols-3 gap-4">
    <div>
        <label class="text-sm font-medium text-slate-700">Tanggal <span class="text-red-500">*</span></label>
        <input type="date" name="date" value="{{ old('date', optional($e?->date)->format('Y-m-d')) }}"
            class="mt-1 w-full rounded-lg border-slate-200 focus:border-[#121293] focus:ring-[#121293]" required>
        <x-input-error :messages="$errors->get('date')" class="mt-2" />
    </div>
    <div>
        <label class="text-sm font-medium text-slate-700">Mulai</label>
        <input type="time" name="start_time" value="{{ old('start_time', $e->start_time ?? '') }}"
            class="mt-1 w-full rounded-lg border-slate-200 focus:border-[#121293] focus:ring-[#121293]">
        <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
    </div>
    <div>
        <label class="text-sm font-medium text-slate-700">Selesai</label>
        <input type="time" name="end_time" value="{{ old('end_time', $e->end_time ?? '') }}"
            class="mt-1 w-full rounded-lg border-slate-200 focus:border-[#121293] focus:ring-[#121293]">
        <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
    </div>
</div>

<div class="grid md:grid-cols-2 gap-4">
    <div>
        <label class="text-sm font-medium text-slate-700">Lokasi</label>
        <input name="location" value="{{ old('location', $e->location ?? '') }}"
            class="mt-1 w-full rounded-lg border-slate-200 focus:border-[#121293] focus:ring-[#121293]">
        <x-input-error :messages="$errors->get('location')" class="mt-2" />
    </div>
    <div>
        <label class="text-sm font-medium text-slate-700">Target Audience</label>
        <input name="target_audience" value="{{ old('target_audience', $e->target_audience ?? '') }}"
            class="mt-1 w-full rounded-lg border-slate-200 focus:border-[#121293] focus:ring-[#121293]">
        <x-input-error :messages="$errors->get('target_audience')" class="mt-2" />
    </div>
</div>

<div>
    <label class="text-sm font-medium text-slate-700">Status <span class="text-red-500">*</span></label>
    <select name="status" class="mt-1 w-full rounded-lg border-slate-200 focus:border-[#121293] focus:ring-[#121293]"
        required>
        @php $status = old('status', $e->status ?? 'scheduled'); @endphp
        <option value="scheduled" @selected($status === 'scheduled')>Scheduled</option>
        <option value="cancelled" @selected($status === 'cancelled')>Cancelled</option>
        <option value="done" @selected($status === 'done')>Done</option>
    </select>
    <x-input-error :messages="$errors->get('status')" class="mt-2" />
</div>

<div class="flex gap-3 pt-2">
    <button class="px-4 py-2 rounded-lg bg-[#121293] text-white hover:opacity-90">
        {{ $submitText ?? 'Simpan' }}
    </button>
    <a href="{{ route('annual-plans.show', $annualPlan) }}"
        class="px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-50">
        Batal
    </a>
</div>
