<x-app-layout>
    <div class="max-w-full mx-auto">
        <h1 class="text-xl font-semibold text-slate-800 mb-4">Edit Job Title</h1>

        <div class="bg-white border border-slate-200 rounded-xl p-6">
            <form method="POST" action="{{ route('job-titles.update', $title) }}">
                @method('PUT')
                @include('job_titles._form', ['title' => $title, 'categories' => $categories])
            </form>
        </div>
    </div>
</x-app-layout>
