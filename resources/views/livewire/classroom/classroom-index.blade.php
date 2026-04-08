<div>
    <x-card title="Admin | Classroom Management" shadow separator>
        <x-button label="Add Classroom" icon="o-plus" wire:click="openModal()" class="btn-primary mb-4" />

        <x-table :headers="[
            ['key' => 'code', 'label' => 'Code'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'floor', 'label' => 'Floor'],
            ['key' => 'building_id', 'label' => 'Building'],
            ['key' => 'action', 'label' => 'Action'],
        ]" :rows="$classrooms">
            @scope('cell_building_id', $classroom)
                {{ $classroom->building->name ?? '-' }}
            @endscope
            @scope('cell_action', $classroom)
                <x-button wire:click="openModal({{ $classroom->id }})" icon="o-pencil" class="btn-circle btn-sm btn-outline" />
                <x-button wire:click="delete({{ $classroom->id }})" icon="o-trash" class="btn-circle btn-sm btn-outline" />
            @endscope
        </x-table>
    </x-card>

    <x-modal wire:model="modal" title="Classroom">
        <x-input label="Code" wire:model="code" />
        <x-input label="Name" wire:model="name" />
        <x-input label="Floor" wire:model="floor" />
        <x-select label="Building" wire:model="building_id" :options="$buildings" option-value="id" option-label="name" />
        <x-slot:actions>
            <x-button label="Cancel" wire:click="$set('modal', false)" />
            <x-button label="Save" wire:click="save" class="btn-primary" />
        </x-slot:actions>
    </x-modal>
</div>