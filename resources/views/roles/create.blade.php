<x-app-layout>
    <div class="max-w-full mx-auto">
        <div class="mb-4">
            <h1 class="text-xl font-semibold text-slate-800">Tambah Role</h1>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl p-6">
            <form method="POST" action="{{ route('roles.store') }}">
                @include('roles._form')
            </form>
        </div>
    </div>
</x-app-layout>
