<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">Approvals</h2>
            <p class="text-sm text-slate-500">Daftar Annual Plan yang menunggu persetujuan.</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                <div class="p-4 border-b border-slate-200 font-semibold text-slate-800">
                    Pending Plans
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="text-left px-4 py-3">Judul</th>
                                <th class="text-left px-4 py-3">Tahun</th>
                                <th class="text-left px-4 py-3">Dibuat</th>
                                <th class="text-right px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse ($plans as $p)
                                <tr class="text-slate-700">
                                    <td class="px-4 py-3 font-medium">{{ $p->title }}</td>
                                    <td class="px-4 py-3">{{ $p->year }}</td>
                                    <td class="px-4 py-3">{{ $p->created_at?->format('d M Y') }}</td>
                                    <td class="px-4 py-3 text-right space-x-2">
                                        <form method="POST" action="{{ route('annual-plans.approve', $p) }}"
                                            class="inline">
                                            @csrf
                                            <button
                                                class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white hover:opacity-90">
                                                Approve
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('annual-plans.reject', $p) }}"
                                            class="inline">
                                            @csrf
                                            <button
                                                class="px-3 py-1.5 rounded-lg bg-red-600 text-white hover:opacity-90">
                                                Reject
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-slate-500">
                                        Tidak ada yang pending.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4">
                    {{ $plans->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
