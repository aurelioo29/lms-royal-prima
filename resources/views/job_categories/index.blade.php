<x-app-layout>
    <div class="max-w-full">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-xl font-semibold text-slate-800">Job Categories</h1>
                <p class="text-sm text-slate-500">Kategori pekerjaan (Tenaga Kesehatan, Non Medis, dll).</p>
            </div>

            <a href="{{ route('job-categories.create') }}"
                class="px-4 py-2 rounded-lg bg-[#121293] text-white text-sm hover:opacity-90">
                + Tambah
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
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="text-left px-4 py-3">Name</th>
                            <th class="text-left px-4 py-3">Slug</th>
                            <th class="text-left px-4 py-3">Status</th>
                            <th class="text-right px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($categories as $c)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $c->name }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $c->slug }}</td>
                                <td class="px-4 py-3">
                                    @if ($c->is_active)
                                        <span
                                            class="px-2 py-1 rounded-full bg-green-50 text-green-700 text-xs border border-green-100">Active</span>
                                    @else
                                        <span
                                            class="px-2 py-1 rounded-full bg-slate-50 text-slate-600 text-xs border border-slate-200">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <a href="{{ route('job-categories.edit', $c) }}"
                                        class="text-[#121293] hover:underline mr-3">Edit</a>
                                    <form action="{{ route('job-categories.destroy', $c) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Hapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-10 text-center text-slate-500">Belum ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
