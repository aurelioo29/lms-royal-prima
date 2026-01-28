<x-app-layout>
    <div class="py-6">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Main Card -->
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="h-1 w-full bg-[#121293]"></div>

                <!-- Header -->
                <div class="p-5 sm:p-6">
                    <h1 class="text-xl font-semibold text-slate-900">
                        Kelola Peserta Course
                    </h1>
                    <p class="text-sm text-slate-600 mt-1">
                        {{ $course->title }}
                    </p>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600 border-y border-slate-100">
                            <tr>
                                <th class="text-left px-6 py-4 font-semibold">Peserta</th>
                                <th class="text-center px-4 py-4 font-semibold">Tanggal Enroll</th>
                                <th class="text-center px-4 py-4 font-semibold">Status</th>
                                <th class="text-right px-6 py-4 font-semibold">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($enrollments as $enrollment)
                                <tr>
                                    <!-- Peserta -->
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-900">
                                            {{ $enrollment->user->name }}
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            {{ $enrollment->user->email }}
                                        </div>
                                    </td>

                                    <!-- Tanggal -->
                                    <td class="px-4 py-4 text-center">
                                        {{ $enrollment->enrolled_at?->format('d M Y') ?? '-' }}
                                    </td>

                                    <!-- Status -->
                                    <td class="px-4 py-4 text-center">
                                        @if ($enrollment->status === 'completed')
                                            <span
                                                class="inline-flex rounded-full bg-blue-100 px-3 py-1
                                                       text-xs font-semibold text-blue-700">
                                                Completed
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex rounded-full bg-green-100 px-3 py-1
                                                       text-xs font-semibold text-green-700">
                                                Aktif
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Aksi -->
                                    <td class="px-6 py-4 text-right" x-data="{ open: false }">

                                        {{-- STATUS COMPLETED --}}
                                        @if ($enrollment->status === 'completed')
                                            <button type="button" @click="open = true"
                                                class="inline-flex items-center rounded-full
                                                       bg-gray-100 text-gray-600
                                                       border border-gray-200
                                                       px-3 py-1 text-xs font-semibold
                                                       cursor-not-allowed">
                                                Tidak dapat dikeluarkan
                                            </button>

                                            <!-- Modal Info -->
                                            <div x-show="open" x-cloak
                                                class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">

                                                <div @click.outside="open = false"
                                                    class="w-full max-w-md rounded-2xl bg-white p-6 shadow-lg text-left">

                                                    <h2 class="text-lg font-semibold text-slate-900">
                                                        Informasi
                                                    </h2>

                                                    <p class="mt-3 text-sm text-slate-600">
                                                        Peserta yang sudah
                                                        <strong>menyelesaikan course</strong>
                                                        tidak dapat dikeluarkan.
                                                    </p>

                                                    <div class="mt-6 flex justify-end">
                                                        <button @click="open = false"
                                                            class="rounded-lg bg-slate-900 px-4 py-2
                       text-sm font-semibold text-white">
                                                            Tutup
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>


                                            {{-- STATUS AKTIF --}}
                                        @else
                                            <form
                                                action="{{ route($routePrefix . '.enrollments.destroy', [$course->id, $enrollment->id]) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="inline-flex items-center rounded-full
                                                           bg-red-50 text-red-700
                                                           border border-red-200
                                                           px-3 py-1 text-xs font-semibold
                                                           hover:bg-red-100 transition">
                                                    Keluarkan
                                                </button>
                                            </form>
                                        @endif

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                                        Belum ada peserta yang terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
