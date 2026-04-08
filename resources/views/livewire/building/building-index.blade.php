<div>
    <x-card title="Admin | Building Management" shadow separator>
        <x-button label="Add Building" icon="o-plus" wire:click="openModal()" class="btn-primary mb-4" />
        
        <x-table :headers="[
    ['key' => 'code', 'label' => 'Code'],
    ['key' => 'name', 'label' => 'Name'],
    ['key' => 'location', 'label' => 'Location'],
    ['key' => 'action', 'label' => 'Action'],
]" :rows="$buildings">
            @scope('cell_action', $building)
                <x-button wire:click="openModal({{ $building->id }})" icon="o-pencil" class="btn-circle btn-sm btn-outline" />
                <x-button wire:click="delete({{ $building->id }})" icon="o-trash" class="btn-circle btn-sm btn-outline" />
            @endscope
        </x-table>
    </x-card>

    <x-modal wire:model="modal" title="Building">
        <x-input label="Code" wire:model="code" />
        <x-input label="Name" wire:model="name" />
        <x-input label="Location" wire:model="location" />
        <x-slot:actions>
            <x-button label="Cancel" wire:click="$set('modal', false)" />
            <x-button label="Save" wire:click="save" class="btn-primary" />
        </x-slot:actions>
    </x-modal>
</div>