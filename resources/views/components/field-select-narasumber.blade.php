{{-- Narasumber --}}
<div>
    <label class="text-sm font-semibold text-slate-700">
        Narasumber (MOT Approved)
    </label>

    <div class="mt-1">
        <select id="select-instructors" name="instructors[]" multiple autocomplete="off" placeholder="Pilih narasumber..."
            class="w-full rounded-xl">
            @foreach ($eligibleInstructors as $inst)
                <option value="{{ $inst->id }}" @selected(in_array($inst->id, old('instructors', $selectedInstructors ?? [])))>
                    {{ $inst->name }}
                </option>
            @endforeach
        </select>
    </div>

    <p class="mt-1 text-xs text-slate-500">
        Ketik nama atau pilih dari daftar. Nama yang terpilih akan muncul di atas.
    </p>

    @error('instructors')
        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
    @enderror
</div>

<script>
    new TomSelect("#select-instructors", {
        plugins: ['remove_button'],
        create: false,
        maxItems: null,
        render: {
            option: function(data, escape) {
                return '<div class="px-3 py-2 text-sm">' + escape(data.text) + '</div>';
            },
            item: function(data, escape) {
                return '<div class="bg-[#121293] text-white rounded-full px-3 py-1 text-xs">' + escape(data
                    .text) + '</div>';
            }
        }
    });
</script>
