<x-app-layout>
    <div class="p-6 max-w-3xl">
        <div class="mb-4">
            <h1 class="text-xl font-semibold text-slate-800">Edit Karyawan</h1>
            <p class="text-sm text-slate-500">Ubah data karyawan. Password boleh dikosongkan jika tidak diganti.</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-5">
            <form method="POST" action="{{ route('employees.update', $employee) }}" class="space-y-4">
                @csrf
                @method('PUT')

                @include('employees._form', [
                    'employee' => $employee,
                    'jobCategories' => $jobCategories,
                    'jobTitles' => $jobTitles,
                    'submitText' => 'Update',
                ])
            </form>
        </div>
    </div>
</x-app-layout>
