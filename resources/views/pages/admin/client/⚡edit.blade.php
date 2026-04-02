<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\BEMS\Client;
use Mary\Traits\Toast;
new class extends Component
{
    //
    public $clientEditModal = false;
    public $code;
    public $name;
    public $expirity;
    public $clientId;
    use Toast;
    #[On('enableEditClient')]
    public function enableEditClient ($clientId){
        $this->clientId = $clientId;
        if($this->clientEditModal == false){
            $client = Client::find($clientId);
            $this->code = $client->code;
            $this->name = $client->name;
            $this->expirity = $client->expirity;
            $this->clientEditModal = true;
        }else{
            $this->clientEditModal = false;
        }
    }

    public function updateClient(){
        $this->validate([
            'code' => 'required|string',
            'name' => 'required|string',
            'expirity' => 'required|date',
        ]);

        Client::find($this->clientId)->update([
            'code' => $this->code,
            'name' => $this->name,
            'expirity' => $this->expirity,
        ]);

        $this->code = null;
        $this->name = null;
        $this->expirity = null;
        $this->success('Client data has been updated!');
        $this->clientEditModal = false;
        $this->dispatch('refreshIndexClient');
    }

};
?>

<div>
    <x-modal wire:model="clientEditModal" title="Edit Client" class="backdrop-blur">
        <div class="text-left">
            <div class="flex flex-wrap -mx-3">
                <div class="w-full max-w-full px-3 mb-6 sm:w-12/12 sm:flex-none xl:mb-0 xl:w-12/12">
                    <x-card subtitle="Add client" shadow separator>
                        <div class="text-left">
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3 mb-6 sm:w-6/12 sm:flex-none xl:mb-0 xl:w-6/12">
                                    <x-input wire:model="code" label="Code" />
                                </div>
                                <div class="w-full max-w-full px-3 mb-6 sm:w-6/12 sm:flex-none xl:mb-0 xl:w-6/12">
                                    <x-input wire:model="name" label="Name of client" />
                                </div>
                            </div>
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3 mb-6 sm:w-6/12 sm:flex-none xl:mb-0 xl:w-6/12">
                                    <x-datetime label="My date" wire:model="expirity" />
                                </div>
                            </div>
                            <br/>
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3 mb-6 sm:w-6/12 sm:flex-none xl:mb-0 xl:w-6/12">
                                    <x-button wire:click="updateClient" class="btn-success" label="update"/>
                                    <x-button wire:click="enableEditClient" class="btn-warning" label="Cancel"/>
                                </div>
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </x-modal>
    {{-- It is not the man who has too little, but the man who craves more, that is poor. - Seneca --}}
</div>
