<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <h1 class="text-xl font-semibold text-slate-800 mb-4">Tambah Job Category</h1>

        <div class="bg-white border border-slate-200 rounded-xl p-6">
            <form method="POST" action="{{ route('job-categories.store') }}">
                @include('job_categories._form')
            </form>
        </div>
    </div>
</x-app-layout>
