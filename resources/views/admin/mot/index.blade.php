<x-app-layout>
    <div class="py-6">
        <div class="max-w-full sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 rounded-xl p-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-xl p-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                <div class="p-4 border-b border-slate-200">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div class="font-semibold text-slate-800">
                            Akun Narasumber (MOT)
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
                                    <button
                                        class="px-3 py-1.5 rounded-lg bg-[#121293] text-white text-xs hover:opacity-90">
                                        Search
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if (request('q'))
                        <div class="mt-2 text-xs text-slate-500">
                            Menampilkan hasil untuk:
                            <span class="font-medium text-slate-700">"{{ request('q') }}"</span>
                        </div>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="text-left px-4 py-3">Narasumber</th>
                                <th class="text-left px-4 py-3">Status MOT</th>
                                <th class="text-left px-4 py-3">Uploaded</th>
                                <th class="text-right px-4 py-3">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">
                            @forelse ($instructors as $u)
                                @php
                                    $mot = $u->latestMot; // bisa null
                                    $status = $mot?->status ?? 'not_uploaded';

                                    $chipClass = match ($status) {
                                        'pending' => 'border-amber-200 bg-amber-50 text-amber-700',
                                        'approved' => 'border-green-200 bg-green-50 text-green-700',
                                        'rejected' => 'border-red-200 bg-red-50 text-red-700',
                                        'not_uploaded' => 'border-slate-200 bg-slate-50 text-slate-600',
                                        default => 'border-slate-200 bg-slate-50 text-slate-600',
                                    };

                                    $label = match ($status) {
                                        'pending' => 'SUBMITTED',
                                        'approved' => 'APPROVED',
                                        'rejected' => 'REJECTED',
                                        'not_uploaded' => 'BELUM UPLOAD',
                                        default => strtoupper($status),
                                    };
                                @endphp

                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-slate-800">{{ $u->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $u->email }}</div>
                                    </td>

                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 rounded-full text-xs border {{ $chipClass }}">
                                            {{ $label }}
                                        </span>

                                        @if ($mot?->rejected_reason)
                                            <div class="mt-2 text-xs text-red-700 line-clamp-2">
                                                <span class="font-semibold">Catatan:</span>
                                                {{ $mot->rejected_reason }}
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 text-slate-500">
                                        @if ($mot)
                                            <div>{{ $mot->created_at?->format('d M Y H:i') }}</div>

                                            @if ($mot->uploaded_by)
                                                <div class="text-xs text-slate-400">
                                                    via: {{ $mot->uploader?->name ?? 'Admin' }}
                                                </div>
                                            @endif
                                        @else
                                            —
                                        @endif
                                    </td>

                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-2">

                                            {{-- kalau belum upload, tampilkan tombol upload --}}
                                            @if (!$mot)
                                                <a href="{{ route('admin.mot.create', $u) }}"
                                                    class="inline-flex items-center justify-center rounded-lg bg-[#121293] px-3 py-2 text-xs font-semibold text-white hover:opacity-90">
                                                    Upload MOT
                                                </a>
                                            @else
                                                <a href="{{ route('admin.mot.show', $mot) }}"
                                                    class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                                    Review
                                                </a>

                                                {{-- kalau mau izinkan re-upload meskipun sudah ada --}}
                                                <a href="{{ route('admin.mot.create', $u) }}"
                                                    class="inline-flex items-center justify-center rounded-lg border border-[#121293]/20 bg-[#121293]/5 px-3 py-2 text-xs font-semibold text-[#121293] hover:bg-[#121293]/10">
                                                    Re-Upload
                                                </a>
                                            @endif

                                            <form method="POST"
                                                action="{{ route('admin.mot.instructors.destroy', $u) }}"
                                                class="js-delete-instructor">
                                                @csrf
                                                @method('DELETE')

                                                <button type="button"
                                                    class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100"
                                                    data-name="{{ $u->name }}">
                                                    Delete Account
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-14 text-center text-slate-600">
                                        Tidak ada narasumber ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4">
                    {{ $instructors->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('.js-delete-instructor button[type="button"]');
        if (!btn) return;

        const form = btn.closest('form');
        const name = btn.dataset.name || 'user ini';

        const result = await Swal.fire({
            title: 'Hapus akun?',
            html: `Akun <b>${name}</b> akan dihapus permanen.<br/>Data terkait bisa ikut hilang.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            focusCancel: true,
        });

        if (result.isConfirmed) {
            form.submit();
        }
    });
</script>
