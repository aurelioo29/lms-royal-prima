<x-app-layout>
    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- SweetAlert Flash Message --}}
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: @json(session('success')),
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true
                        });
                    });
                </script>
            @endif

            @if (session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: @json(session('error')),
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    });
                </script>
            @endif

            {{-- Page Header --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="p-5 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <div class="min-w-0">
                            <h1 class="text-lg sm:text-xl font-semibold text-slate-900">
                                Annual Plans
                            </h1>
                            <p class="mt-1 text-sm text-slate-600">
                                Manajemen rencana tahunan pelatihan dan alur persetujuannya.
                            </p>

                            <div
                                class="mt-3 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-600">
                                <span class="font-semibold text-slate-800">Keterangan:</span>
                                Annual Plan → Event → TOR → Course (tahap berikutnya diproses setelah tahap sebelumnya
                                disetujui).
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            @if (auth()->user()->canApprovePlans())
                                <a href="{{ route('annual-plans.approvals') }}"
                                    class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                    Approvals
                                </a>
                            @endif

                            @if (auth()->user()->canCreatePlans())
                                <a href="{{ route('annual-plans.create') }}"
                                    class="inline-flex items-center justify-center rounded-lg bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                    Buat Annual Plan
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Toolbar --}}
                    <form method="GET" action="{{ route('annual-plans.index') }}" class="mt-5">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3">
                            <div class="lg:col-span-6">
                                <label class="sr-only">Pencarian</label>
                                <div class="relative">
                                    <span
                                        class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" aria-hidden="true">
                                            <path fill="currentColor"
                                                d="M10 2a8 8 0 1 0 4.9 14.3l4.4 4.4l1.4-1.4l-4.4-4.4A8 8 0 0 0 10 2Zm0 2a6 6 0 1 1 0 12a6 6 0 0 1 0-12Z" />
                                        </svg>
                                    </span>
                                    <input name="q" value="{{ $q ?? request('q') }}" type="text"
                                        placeholder="Cari judul, tahun, creator"
                                        class="w-full rounded-lg border border-slate-200 bg-white pl-10 pr-3 py-2 text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#121293]/20" />
                                </div>
                            </div>

                            <div class="lg:col-span-3">
                                <label class="sr-only">Status</label>
                                <select name="status"
                                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#121293]/20">
                                    <option value="">Semua Status</option>
                                    <option value="draft" @selected(($status ?? request('status')) === 'draft')>Draft</option>
                                    <option value="pending" @selected(($status ?? request('status')) === 'pending')>Pending</option>
                                    <option value="approved" @selected(($status ?? request('status')) === 'approved')>Approved</option>
                                    <option value="rejected" @selected(($status ?? request('status')) === 'rejected')>Rejected</option>
                                </select>
                            </div>

                            <div class="lg:col-span-3">
                                <div class="flex gap-2">
                                    <label class="sr-only">Sort</label>
                                    <select name="sort"
                                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#121293]/20">
                                        <option value="newest" @selected(($sort ?? request('sort', 'newest')) === 'newest')>Terbaru</option>
                                        <option value="oldest" @selected(($sort ?? request('sort')) === 'oldest')>Terlama</option>
                                        <option value="year" @selected(($sort ?? request('sort')) === 'year')>Tahun</option>
                                    </select>

                                    <button type="submit"
                                        class="inline-flex items-center justify-center rounded-lg bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                        Terapkan
                                    </button>

                                    <a href="{{ route('annual-plans.index') }}"
                                        class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <div class="text-sm font-semibold text-slate-900">
                        Daftar Annual Plan
                        <span class="ml-2 text-xs font-medium text-slate-500">({{ $plans->total() }} data)</span>
                    </div>
                    <div class="text-xs text-slate-500">Klik “Detail” untuk melihat event & progres</div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                <th class="text-left px-5 py-3 font-semibold">Tahun</th>
                                <th class="text-left px-5 py-3 font-semibold">Judul</th>
                                <th class="text-left px-5 py-3 font-semibold">Creator</th>
                                <th class="text-left px-5 py-3 font-semibold">Status</th>
                                <th class="text-left px-5 py-3 font-semibold">Approver</th>
                                <th class="text-right px-5 py-3 font-semibold">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-200">
                            @forelse($plans as $p)
                                @php
                                    $user = auth()->user();
                                    $canDeleteByRole = $user->canCreatePlans() || $user->canApprovePlans();
                                    $canDeleteByStatus = in_array($p->status, ['draft', 'rejected', 'approved'], true);
                                @endphp

                                <tr class="hover:bg-slate-50">
                                    <td class="px-5 py-4 font-semibold text-slate-900">{{ $p->year }}</td>

                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-slate-900">{{ $p->title }}</div>
                                        <div class="mt-1 text-slate-600 text-sm line-clamp-1">
                                            {{ $p->description ?: '—' }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4 text-slate-700">
                                        <div class="font-medium">{{ $p->creator?->name ?: '—' }}</div>
                                        <div class="text-xs text-slate-500">
                                            Dibuat: {{ optional($p->created_at)->format('d M Y') ?: '—' }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        <x-status-badge :status="$p->status" label="Plan" />
                                    </td>

                                    <td class="px-5 py-4 text-slate-700">
                                        <div class="font-medium">{{ $p->approver?->name ?: '—' }}</div>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('annual-plans.show', $p) }}"
                                                class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                                Detail
                                            </a>

                                            @if (auth()->user()->canCreatePlans() && in_array($p->status, ['draft', 'rejected', 'approved'], true))
                                                <a href="{{ route('annual-plans.edit', $p) }}"
                                                    class="inline-flex items-center justify-center rounded-lg bg-[#121293] px-3 py-2 text-sm font-semibold text-white hover:opacity-90">
                                                    Edit
                                                </a>
                                            @endif

                                            @if ($canDeleteByRole && $canDeleteByStatus)
                                                <form method="POST" action="{{ route('annual-plans.destroy', $p) }}"
                                                    class="js-delete-form inline-block"
                                                    data-delete-title="Hapus Annual Plan?"
                                                    data-delete-text="Annual Plan '{{ $p->title }}' akan dihapus. Tindakan ini tidak bisa dibatalkan."
                                                    data-confirm-text="Ya, hapus">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-semibold text-red-700 hover:bg-red-100">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12">
                                        <div class="text-center">
                                            <div class="text-slate-900 font-semibold">Belum ada Annual Plan</div>
                                            <div class="text-slate-600 text-sm mt-1">
                                                Buat Annual Plan untuk memulai penyusunan Event → TOR → Course.
                                            </div>

                                            @if (auth()->user()->canCreatePlans())
                                                <div class="mt-4">
                                                    <a href="{{ route('annual-plans.create') }}"
                                                        class="inline-flex items-center justify-center rounded-lg bg-[#121293] px-4 py-2 text-sm font-semibold text-white hover:opacity-90">
                                                        Buat Annual Plan
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-slate-200 p-4">
                    {{ $plans->links() }}
                </div>
            </div>

        </div>
    </div>

    {{-- SweetAlert Delete Confirm --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.js-delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const title = form.dataset.deleteTitle || 'Hapus data?';
                    const text = form.dataset.deleteText || 'Tindakan ini tidak bisa dibatalkan.';
                    const confirmText = form.dataset.confirmText || 'Ya, hapus';

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: confirmText,
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>
