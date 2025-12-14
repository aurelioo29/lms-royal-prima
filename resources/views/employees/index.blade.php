@php
    $currentSort = request('sort');
    $currentDir = request('dir', 'asc');

    function sort_link($key)
    {
        $currentSort = request('sort');
        $currentDir = request('dir', 'asc');

        $dir = $currentSort === $key && $currentDir === 'asc' ? 'desc' : 'asc';

        return request()->fullUrlWithQuery([
            'sort' => $key,
            'dir' => $dir,
            'page' => 1,
        ]);
    }

    function sort_icon($key)
    {
        $currentSort = request('sort');
        $currentDir = request('dir', 'asc');

        if ($currentSort !== $key) {
            return '↕';
        }
        return $currentDir === 'asc' ? '↑' : '↓';
    }
@endphp

<x-app-layout>
    <div class="p-6">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-4">
            <div>
                <h1 class="text-xl font-semibold text-slate-800">Karyawan</h1>
                <p class="text-sm text-slate-500">Kelola data karyawan (role: employee).</p>
            </div>

            <a href="{{ route('employees.create') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-[#121293] text-white hover:opacity-90">
                <span class="text-lg leading-none">+</span> Tambah Karyawan
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 p-3 text-green-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
            <div class="p-4 border-b border-slate-200 bg-slate-50/50">
                <form method="GET" class="flex flex-wrap md:flex-nowrap items-end gap-3">

                    {{-- Search --}}
                    <div class="w-full md:flex-1 min-w-[260px]">
                        <label class="block text-xs font-medium text-slate-600 mb-1">Search</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-slate-400">⌕</span>
                            <input name="q" value="{{ $q }}"
                                class="w-full pl-9 rounded-xl border-slate-200 bg-white focus:border-[#121293] focus:ring-[#121293]"
                                placeholder="Name / email / NIK / phone..." />
                        </div>
                    </div>

                    {{-- Job Category --}}
                    <div class="w-full md:w-[240px]">
                        <label class="block text-xs font-medium text-slate-600 mb-1">Job Category</label>
                        <select name="job_category_id"
                            class="w-full rounded-xl border-slate-200 bg-white focus:border-[#121293] focus:ring-[#121293]">
                            <option value="">All Categories</option>
                            @foreach ($jobCategories as $c)
                                <option value="{{ $c->id }}" @selected((string) $jobCategoryId === (string) $c->id)>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Job Title --}}
                    <div class="w-full md:w-[240px]">
                        <label class="block text-xs font-medium text-slate-600 mb-1">Job Title</label>
                        <select name="job_title_id"
                            class="w-full rounded-xl border-slate-200 bg-white focus:border-[#121293] focus:ring-[#121293]">
                            <option value="">All Job Titles</option>
                            @foreach ($jobTitles as $t)
                                <option value="{{ $t->id }}" @selected((string) $jobTitleId === (string) $t->id)>
                                    {{ $t->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- keep sort params --}}
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                    <input type="hidden" name="dir" value="{{ request('dir', 'asc') }}">

                    {{-- Buttons --}}
                    <div class="w-full md:w-auto flex gap-2">
                        <button
                            class="flex-1 md:flex-none inline-flex items-center justify-center px-4 py-2 rounded-xl bg-[#121293] text-white hover:opacity-90 shadow-sm">
                            Cari
                        </button>

                        <a href="{{ route('employees.index') }}"
                            class="flex-1 md:flex-none inline-flex items-center justify-center px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700">
                            Reset
                        </a>
                    </div>

                    {{-- Chips (push to the right on desktop) --}}
                    <div class="w-full md:w-auto md:ml-auto flex flex-wrap justify-start md:justify-end gap-2">
                        @if ($q)
                            <span
                                class="px-3 py-1 rounded-full text-xs bg-white border border-slate-200 text-slate-700">
                                Search: <span class="font-medium">{{ $q }}</span>
                            </span>
                        @endif
                        @if ($jobCategoryId)
                            <span
                                class="px-3 py-1 rounded-full text-xs bg-white border border-slate-200 text-slate-700">
                                Category filtered
                            </span>
                        @endif
                        @if ($jobTitleId)
                            <span
                                class="px-3 py-1 rounded-full text-xs bg-white border border-slate-200 text-slate-700">
                                Title filtered
                            </span>
                        @endif
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="text-left px-4 py-3">Nama</th>
                            <th class="text-left px-4 py-3">Email</th>
                            <th class="text-left px-4 py-3">NIK</th>
                            <th class="text-left px-4 py-3">
                                <a href="{{ sort_link('job_category') }}"
                                    class="inline-flex items-center gap-1 font-medium hover:text-[#121293]">
                                    Job Category
                                    <span class="text-xs text-slate-400">
                                        {{ sort_icon('job_category') }}
                                    </span>
                                </a>
                            </th>
                            <th class="text-left px-4 py-3">
                                <a href="{{ sort_link('job_title') }}"
                                    class="inline-flex items-center gap-1 font-medium hover:text-[#121293]">
                                    Job Title
                                    <span class="text-xs text-slate-400">
                                        {{ sort_icon('job_title') }}
                                    </span>
                                </a>
                            </th>

                            <th class="text-left px-4 py-3">Status</th>
                            <th class="text-right px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($employees as $e)
                            <tr class="hover:bg-slate-50/60">
                                <td class="px-4 py-3 font-medium text-slate-800">{{ $e->name }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $e->email }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $e->nik }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $e->jobCategory->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $e->jobTitle->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @if ($e->is_active)
                                        <span
                                            class="px-2 py-1 rounded-full text-xs bg-green-50 text-green-700 border border-green-200">
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-1 rounded-full text-xs bg-slate-50 text-slate-600 border border-slate-200">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('employees.edit', $e) }}"
                                            class="text-[#121293] hover:underline">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('employees.destroy', $e) }}"
                                            onsubmit="return confirm('Hapus karyawan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 hover:underline">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-slate-500">
                                    Belum ada data karyawan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                {{ $employees->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
