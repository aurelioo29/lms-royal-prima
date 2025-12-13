<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-xl font-semibold text-slate-800">Roles</h1>
                <p class="text-sm text-slate-500">Kelola role & permission dasar.</p>
            </div>

            <a href="{{ route('roles.create') }}"
                class="px-4 py-2 rounded-lg bg-[#121293] text-white text-sm hover:opacity-90">
                + Tambah Role
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm border border-green-100">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm border border-red-100">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
            <div class="p-4 border-b">
                <form method="GET" class="flex gap-2">
                    <input name="q" value="{{ $q }}"
                        class="w-full rounded-lg border-slate-200 focus:border-slate-300 focus:ring-slate-200"
                        placeholder="Cari name/slug...">
                    <button class="px-4 py-2 rounded-lg bg-slate-900 text-white text-sm">Cari</button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="text-left px-4 py-3">Name</th>
                            <th class="text-left px-4 py-3">Slug</th>
                            <th class="text-left px-4 py-3">Level</th>
                            <th class="text-left px-4 py-3">Can Manage Users</th>
                            <th class="text-right px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($roles as $role)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $role->name }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $role->slug }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $role->level }}</td>
                                <td class="px-4 py-3">
                                    @if ($role->can_manage_users)
                                        <span
                                            class="px-2 py-1 rounded-full bg-green-50 text-green-700 text-xs border border-green-100">Yes</span>
                                    @else
                                        <span
                                            class="px-2 py-1 rounded-full bg-slate-50 text-slate-600 text-xs border border-slate-200">No</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <a href="{{ route('roles.edit', $role) }}"
                                        class="text-[#121293] hover:underline mr-3">Edit</a>

                                    <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Hapus role ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        @if ($roles->isEmpty())
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-slate-500">
                                    Tidak ada data role.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t">
                {{ $roles->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
