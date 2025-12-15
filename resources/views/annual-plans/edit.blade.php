<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-lg font-semibold text-slate-800">Edit Annual Plan</h2>
            <p class="text-sm text-slate-500">Hanya bisa edit saat draft / rejected.</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 rounded-xl p-6">
                <form method="POST" action="{{ route('annual-plans.update', $annualPlan) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-slate-700">Tahun <span
                                    class="text-red-500">*</span></label>
                            <input name="year" type="number" value="{{ old('year', $annualPlan->year) }}"
                                class="mt-1 w-full rounded-lg border-slate-200 focus:border-[#121293] focus:ring-[#121293]"
                                required>
                            <x-input-error :messages="$errors->get('year')" class="mt-2" />
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-700">Judul <span
                                    class="text-red-500">*</span></label>
                            <input name="title" value="{{ old('title', $annualPlan->title) }}"
                                class="mt-1 w-full rounded-lg border-slate-200 focus:border-[#121293] focus:ring-[#121293]"
                                required>
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700">Deskripsi</label>
                        <textarea name="description" rows="4"
                            class="mt-1 w-full rounded-lg border-slate-200 focus:border-[#121293] focus:ring-[#121293]">{{ old('description', $annualPlan->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="flex gap-3">
                        <button class="px-4 py-2 rounded-lg bg-[#121293] text-white hover:opacity-90">Update</button>
                        <a href="{{ route('annual-plans.show', $annualPlan) }}"
                            class="px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-50">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
