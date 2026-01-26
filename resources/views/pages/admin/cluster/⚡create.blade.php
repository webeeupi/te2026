<?php

use Livewire\Component;
use App\Models\ST\ClusterBase;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Mary\Traits\Toast;

new class extends Component
{
    //
    use Toast;
    public $createClusterModal = false;
    public $clusterName;
    public $clusterCode;


    #[On('AdminCluster_Create')]
    public function clientCluster_Create(){
        $this->createClusterModal = true;
    }
    public function save(){
        $this->validate([
            'clusterName' => 'required|unique:st_cluster_base,name',
            'clusterCode' => 'required|unique:st_cluster_base,code',
        ]);
        ClusterBase::create([
            'name' => $this->clusterName,
            'code' => $this->clusterCode,
        ]);
        $this->success('Cluster has been saved.', position: 'toast-top toast-center');
        $this->createClusterModal = false;
        $this->dispatch('AdminProgramCreate_Refresh');

    }
};
?>

<div>
    <x-modal wire:model="createClusterModal" title="Create Cluster" subtitle="Add cluster data" separator>
        <div class="text-left">
            <div class="flex flex-wrap -mx-3">
                <div class="w-full max-w-full px-3 mb-6 sm:w-2/4 sm:flex-none xl:mb-0 xl:w-2/4">
                    <x-input wire:model="clusterCode" label="Cluster abbreviation"/>
                </div>
            </div>
            <br/>
            <div class="flex flex-wrap -mx-3">
                <div class="w-full max-w-full px-3 mb-6 sm:w-3/4 sm:flex-none xl:mb-0 xl:w-3/4">
                    <x-input wire:model="clusterName" label="Cluster name"/>
                </div>
            </div>
            <br/>
            <div class="flex flex-wrap -mx-3">
                <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-2/4">
                    <x-button wire:click="save" class="btn btn-success btn-sm" label="Save"/>
                </div>
            </div>
        </div>

    </x-modal>
    {{-- The whole future lies in uncertainty: live immediately. - Seneca --}}
</div>
