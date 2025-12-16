<x-app-layout>
    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="h-1 w-full bg-[#121293]"></div>
                <div class="p-5 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <div>
                            <h2 class="text-xl sm:text-2xl font-semibold text-slate-900 tracking-tight">
                                Edit Course
                            </h2>
                            <p class="mt-1 text-sm text-slate-500">
                                {{ $course->title }}
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <a href="{{ route('courses.index') }}"
                                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-700">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="p-5 sm:p-6">
                    <form method="POST" action="{{ route('courses.update', $course) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        @include('courses.partials.form', [
                            'course' => $course,
                            'tor' => $course->torSubmission ?? null,
                            'types' => $types,
                            'submitText' => 'Update Course',
                        ])
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
