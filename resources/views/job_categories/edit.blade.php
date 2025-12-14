<x-app-layout>
    <div class="max-w-full mx-auto">
        <h1 class="text-xl font-semibold text-slate-800 mb-4">Edit Job Category</h1>

        <div class="bg-white border border-slate-200 rounded-xl p-6">
            <form method="POST" action="{{ route('job-categories.update', $category) }}">
                @method('PUT')
                @include('job_categories._form', ['category' => $category])
            </form>
        </div>
    </div>
</x-app-layout>
