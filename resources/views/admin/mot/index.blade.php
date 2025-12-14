<x-app-layout>
    <div class="max-w-full sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 rounded-xl p-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
            <div class="p-4 border-b border-slate-200">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="font-semibold text-slate-800">
                        Daftar Dokumen
                    </div>

                    <form method="GET" action="{{ route('admin.mot.index') }}" class="w-full sm:w-[360px]">
                        <div class="relative">
                            <input type="text" name="q" value="{{ request('q') }}"
                                placeholder="Search nama / email..."
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 pr-24 text-sm focus:border-[#121293] focus:ring-[#121293]/20" />

                            <div class="absolute inset-y-0 right-2 flex items-center gap-2">
                                @if (request('q'))
                                    <a href="{{ route('admin.mot.index') }}"
                                        class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs text-slate-600 hover:bg-slate-50">
                                        Clear
                                    </a>
                                @endif
                                <button class="px-3 py-1.5 rounded-lg bg-[#121293] text-white text-xs hover:opacity-90">
                                    Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                @if (request('q'))
                    <div class="mt-2 text-xs text-slate-500">
                        Menampilkan hasil untuk: <span class="font-medium text-slate-700">"{{ request('q') }}"</span>
                    </div>
                @endif
            </div>


            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="text-left px-4 py-3">Narasumber</th>
                            <th class="text-left px-4 py-3">Status</th>
                            <th class="text-left px-4 py-3">Uploaded</th>
                            <th class="text-right px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($docs as $d)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-slate-800">{{ $d->user?->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $d->user?->email }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <span @class([
                                        'px-2 py-1 rounded-full text-xs border',
                                        'border-amber-200 bg-amber-50 text-amber-700' => $d->status === 'pending',
                                        'border-green-200 bg-green-50 text-green-700' => $d->status === 'approved',
                                        'border-red-200 bg-red-50 text-red-700' =>
                                            $d->status !== 'pending' && $d->status !== 'approved',
                                    ])>
                                        {{ ucfirst($d->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-500">{{ $d->created_at->format('d M Y H:i') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.mot.show', $d) }}"
                                        class="text-[#121293] hover:underline">Review</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                {{ $docs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
