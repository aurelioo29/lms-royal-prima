<x-app-layout>
    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="h-1 w-full bg-[#121293]"></div>
                <div class="p-5 sm:p-6 flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-semibold text-slate-900 tracking-tight">
                            Tambah Course Type
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">Buat kategori course baru.</p>
                    </div>
                    <a href="{{ route('course-types.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Kembali
                    </a>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="p-5 sm:p-6">
                    <form method="POST" action="{{ route('course-types.store') }}" class="space-y-6">
                        @csrf
                        @include('course-types.partials.form', [
                            'courseType' => null,
                            'submitText' => 'Tambah',
                        ])
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
