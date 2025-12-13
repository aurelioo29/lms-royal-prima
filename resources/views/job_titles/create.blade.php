<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <h1 class="text-xl font-semibold text-slate-800 mb-4">Tambah Job Title</h1>

        <div class="bg-white border border-slate-200 rounded-xl p-6">
            <form method="POST" action="{{ route('job-titles.store') }}">
                @include('job_titles._form', ['categories' => $categories])
            </form>
        </div>
    </div>
</x-app-layout>
