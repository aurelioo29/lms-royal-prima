@props([
    'type' => 'success', // success | error | warning | info
    'message' => '',
])

@php
    $config = [
        'success' => [
            'icon' =>
                '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />',
            'color' => 'text-emerald-500',
            'bg' => 'bg-emerald-50',
            'border' => 'border-emerald-100',
        ],
        'error' => [
            'icon' =>
                '<path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />',
            'color' => 'text-rose-500',
            'bg' => 'bg-rose-50',
            'border' => 'border-rose-100',
        ],
        'warning' => [
            'icon' =>
                '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />',
            'color' => 'text-amber-500',
            'bg' => 'bg-amber-50',
            'border' => 'border-amber-100',
        ],
        'info' => [
            'icon' =>
                '<path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />',
            'color' => 'text-blue-500',
            'bg' => 'bg-blue-50',
            'border' => 'border-blue-100',
        ],
    ];

    $current = $config[$type] ?? $config['info'];
@endphp

<div x-data="{
    show: false,
    init() {
        this.$nextTick(() => { this.show = true });
        setTimeout(() => this.show = false, 4000);
    }
}" x-show="show" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-4"
    x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" class="fixed top-4 right-4 z-[99] w-[calc(100%-2rem)] max-w-sm"
    style="display: none;">
    <div
        class="flex items-center gap-4 p-4 rounded-xl border shadow-xl bg-white dark:bg-gray-800 {{ $current['border'] }}">
        <!-- Icon Container -->
        <div
            class="flex-shrink-0 flex items-center justify-center w-10 h-10 rounded-lg {{ $current['bg'] }} {{ $current['color'] }}">
            <svg xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" fill="none" viewBox="0 0 24 24"
                stroke-width="2" stroke="currentColor" class="w-6 h-6">
                {!! $current['icon'] !!}
            </svg>
        </div>

        <!-- Message Body -->
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 capitalize">
                {{ $type }}
            </p>
            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                {{ $message }}
            </p>
        </div>

        <!-- Close Button -->
        <button @click="show = false"
            class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors p-1">
            <svg xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" fill="none" viewBox="0 0 24 24"
                stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>
