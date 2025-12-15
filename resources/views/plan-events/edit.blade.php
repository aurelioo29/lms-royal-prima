<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">Edit Event</h2>
            <p class="text-sm text-slate-500">Plan: {{ $annualPlan->title }}</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 rounded-xl p-6">
                <form method="POST" action="{{ route('annual-plans.events.update', [$annualPlan, $planEvent]) }}"
                    class="space-y-4">
                    @csrf
                    @method('PUT')
                    @include('plan-events.partials.form', [
                        'planEvent' => $planEvent,
                        'submitText' => 'Update',
                    ])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
