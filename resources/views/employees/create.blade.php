<x-app-layout>
    <div class="p-6 max-w-full">
        <div class="mb-4">
            <h1 class="text-xl font-semibold text-slate-800">Tambah Karyawan</h1>
            <p class="text-sm text-slate-500">Buat user baru untuk karyawan (role employee).</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <form method="POST" action="{{ route('employees.store') }}" class="space-y-4">
                @csrf

                @include('employees._form', [
                    'employee' => null,
                    'jobCategories' => $jobCategories,
                    'jobTitles' => $jobTitles,
                    'submitText' => 'Simpan',
                ])
            </form>
        </div>
    </div>
</x-app-layout>
