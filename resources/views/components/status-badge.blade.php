@props(['status' => 'draft', 'label' => null])

@php
    $s = strtolower($status ?? 'draft');

    $classes = match ($s) {
        'draft' => 'bg-slate-100 text-slate-700 border-slate-200',
        'pending', 'submitted' => 'bg-amber-50 text-amber-800 border-amber-200',
        'approved' => 'bg-green-50 text-green-800 border-green-200',
        'rejected' => 'bg-red-50 text-red-800 border-red-200',
        'published' => 'bg-green-50 text-green-800 border-green-200',
        'archived' => 'bg-slate-100 text-slate-700 border-slate-200',
        default => 'bg-slate-100 text-slate-700 border-slate-200',
    };
@endphp

<span class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-semibold {{ $classes }}">
    <span class="h-2 w-2 rounded-full bg-current opacity-60"></span>
    {{ $label ? $label . ': ' : '' }}{{ strtoupper($s) }}
</span>
