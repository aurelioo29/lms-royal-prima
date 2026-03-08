<div x-data="{ role: '' }">

    <label class="text-sm font-semibold text-slate-700">
        Trainer Role
    </label>

    <div class="flex flex-wrap gap-3 mt-2">
        @foreach ($trainerRoles as $role)
            <label class="flex items-center gap-2 border rounded-xl px-3 py-2 cursor-pointer">
                <input type="radio"
                       name="trainer_role"
                       value="{{ $role->slug }}"
                       x-model="role">

                <span class="text-sm">
                    {{ $role->name }}
                </span>
            </label>
        @endforeach
    </div>

    <div class="mt-4">
        <label class="text-sm font-semibold text-slate-700">
            Trainer
        </label>

        <select id="select-instructors"
                name="instructors[]"
                multiple
                autocomplete="off"
                placeholder="Pilih trainer..."
                class="w-full rounded-xl">

            @foreach ($eligibleInstructors as $inst)
                <option
                    value="{{ $inst->id }}"
                    data-role="{{ $inst->role->slug }}"
                    @selected(in_array($inst->id, old('instructors', $selectedInstructors ?? [])))>

                    {{ $inst->name }} ({{ $inst->role->name }})

                </option>
            @endforeach

        </select>
    </div>

</div>

<script>

let trainerSelect = new TomSelect("#select-instructors", {
    plugins: ['remove_button'],
    create: false,
    maxItems: null
});

document.querySelectorAll("input[name='trainer_role']").forEach(radio => {

    radio.addEventListener("change", function(){

        let role = this.value;

        trainerSelect.clear();
        trainerSelect.clearOptions();

        @foreach($eligibleInstructors as $inst)

            if(role === "{{ $inst->role->slug }}"){

                trainerSelect.addOption({
                    value: "{{ $inst->id }}",
                    text: "{{ $inst->name }} ({{ $inst->role->name }})"
                });

            }

        @endforeach

        trainerSelect.refreshOptions();

    });

});

</script>
