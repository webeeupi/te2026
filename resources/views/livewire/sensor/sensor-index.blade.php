<div>
    <x-card title="Admin | Sensor Management" shadow separator>
        <x-button label="Add Sensor" icon="o-plus" wire:click="openModal()" class="btn-primary mb-4" />

        <x-table :headers="[
            ['key' => 'name', 'label' => 'Sensor Name'],
            ['key' => 'classroom_id', 'label' => 'Classroom'],
            ['key' => 'value', 'label' => 'Value'],
            ['key' => 'unit', 'label' => 'Unit'],
            ['key' => 'last_updated', 'label' => 'Last Updated'],
            ['key' => 'action', 'label' => 'Action'],
        ]" :rows="$sensors">
            @scope('cell_classroom_id', $sensor)
                {{ $sensor->classroom->name ?? '-' }} - {{ $sensor->classroom->building->name ?? '-' }}
            @endscope
            @scope('cell_value', $sensor)
                {{ $sensor->value ?? '-' }}
            @endscope
            @scope('cell_last_updated', $sensor)
                {{ $sensor->last_updated ?? '-' }}
            @endscope
            @scope('cell_action', $sensor)
                <x-button wire:click="openModal({{ $sensor->id }})" icon="o-pencil" class="btn-circle btn-sm btn-outline" />
                <x-button wire:click="delete({{ $sensor->id }})" icon="o-trash" class="btn-circle btn-sm btn-outline" />
            @endscope
        </x-table>
    </x-card>

    <x-modal wire:model="modal" title="Sensor">
        <x-input label="Sensor Name" wire:model="name" />
        <x-input label="Unit" wire:model="unit" />
        <x-select label="Classroom" wire:model="classroom_id" :options="$classrooms" option-value="id" option-label="name" />
        <x-slot:actions>
            <x-button label="Cancel" wire:click="$set('modal', false)" />
            <x-button label="Save" wire:click="save" class="btn-primary" />
        </x-slot:actions>
    </x-modal>
</div>