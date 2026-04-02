<?php

use Livewire\Component;
use App\Models\BEMS\Client;
use Mary\Traits\Toast;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

new class extends Component
{
    //
    use Toast;
    public $enableClientCreate = false;
    public $code;
    public $name;
    public $expirity;

    public function enableClientCreation(){
        if($this->enableClientCreate == false){
            $this->enableClientCreate = true;
        }else{
            $this->enableClientCreate = false;
        }
    }

    public function saveClient(){
        $this->validate([
            'code' => 'required|string',
            'name' => 'required|string',
            'expirity' => 'required|date',
        ]);

        $user = User::create([
            'name' => $this->code,
            'email' => $this->code."@bems.id",
            'password' => Hash::make($this->code."1809##")
        ]);

        Client::create([
            'code' => $this->code,
            'name' => $this->name,
            'user_id' => $user->id,
            'expirity' => $this->expirity,
        ]);

        $this->code = null;
        $this->name = null;
        $this->expirity = null;
        $this->success('Client data has been saved!');
    }

};
?>

<div>
    @if($enableClientCreate == false)
        <div class="text-left">
            <div class="flex flex-wrap -mx-3">
                <div class="w-full max-w-full px-3 mb-6 sm:w-12/12 sm:flex-none xl:mb-0 xl:w-12/12 text-right">
                    <x-button wire:click="enableClientCreation" label="Add Client" class="btn-success" />
                </div>
            </div>
        </div>
    @else
        <div class="text-left">
            <div class="flex flex-wrap -mx-3">
                <div class="w-full max-w-full px-3 mb-6 sm:w-12/12 sm:flex-none xl:mb-0 xl:w-12/12">
                    <x-card subtitle="Add client" shadow separator>
                        <div class="text-left">
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3 mb-6 sm:w-3/12 sm:flex-none xl:mb-0 xl:w-3/12">
                                    <x-input wire:model="code" label="Code" />
                                </div>
                                <div class="w-full max-w-full px-3 mb-6 sm:w-4/12 sm:flex-none xl:mb-0 xl:w-4/12">
                                    <x-input wire:model="name" label="Name of client" />
                                </div>
                            </div>
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3 mb-6 sm:w-4/12 sm:flex-none xl:mb-0 xl:w-4/12">
                                    <x-datetime label="My date" wire:model="expirity" />
                                </div>
                            </div>
                            <br/>
                            <div class="flex flex-wrap -mx-3">
                                <div class="w-full max-w-full px-3 mb-6 sm:w-4/12 sm:flex-none xl:mb-0 xl:w-4/12">
                                    <x-button wire:click="saveClient" class="btn-success" label="Save"/>
                                    <x-button wire:click="enableClientCreation" class="btn-warning" label="Cancel"/>
                                </div>
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    @endif

    {{-- When there is no desire, all things are at peace. - Laozi --}}
</div>
