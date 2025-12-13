<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-4">
            <h1 class="text-xl font-semibold text-slate-800">Edit Role</h1>
            <p class="text-sm text-slate-500">Role: {{ $role->name }}</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-6">
            <form method="POST" action="{{ route('roles.update', $role) }}">
                @method('PUT')
                @include('roles._form', ['role' => $role])
            </form>
        </div>
    </div>
</x-app-layout>
