<?php

use Livewire\Component;
use App\Models\ST\Cluster;
use App\Models\ST\ClusterBase;
use App\Models\ST\Program;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Mary\Traits\Toast;

new class extends Component
{
    //
    use Toast;
    public $clusterSearchable;
    public $cluster_searchable_id = null;
    public $name;
    public $code;
    public $abbrev;
    public $password;
    public $email;
    public $description;
    public  $createProgramEnableState = false;

    #[On('AdminProgramCreate_Refresh')]
    public function refreshData()
    {
        $this->dispatch('$refresh');
        $this->clusterSelect();
    }
    #[On('AdminDataProgramIdx_createProgramEnable')]
    public function createProgramEnable(){
        if($this->createProgramEnableState == true){
            $this->createProgramEnableState = false;
        }else{
            $this->createProgramEnableState = true;
            $this->clusterSelect();
        }
    }

    public function clusterSelect(string $value = '')
    {
        // Besides the search results, you must include on demand selected option
        $selectedOption = ClusterBase::where('id', $this->cluster_searchable_id)->get();
        //$this->faculties = $selectedOption;
        $this->clusterSearchable = ClusterBase::query()
            ->where('code', 'like', "%$value%")
            ->orwhere('name', 'like', "%$value%")
            ->take(5)
            ->get()
            ->merge($selectedOption);     // <-- Adds selected option
    }
    public function save(){
        $this->validate([
            'cluster_searchable_id' => 'required',
            'name' => 'required|string|unique:st_program,name',
            'code' => 'required|string|unique:st_program,code',
            'abbrev' =>'required|string|unique:st_program,abbrev',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string',
        ]);

        $user = User::create([
            'name' => $this->abbrev,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        $user->assignRole('program');
        //$user = User::where('email', $this->email)->first();
        $program = Program::create([
            'name' => $this->name,
            'code' => $this->code,
            'abbrev' => $this->abbrev,
            'user_id' => $user->id,
        ]);
        if(!is_null($this->cluster_searchable_id)){
            Cluster::create([
                'program_id' => $program->id,
                'cluster_base_id' => $this->cluster_searchable_id,
            ]);
        }
        $this->name = null;
        $this->code = null;
        $this->abbrev = null;
        $this->description = null;
        $this->password = null;
        $this->email = null;
        $this->cluster_searchable_id = null;
        $this->success('Program has been saved.', position: 'toast-top toast-center');
    }
};
?>

<div>
    @if($this->createProgramEnableState)
        <div class="text-left">
            <x-card subtitle="Add Program" shadow separator class="p-2 bg-warning/20">
                <div class="flex flex-wrap -mx-3">
                    <div class="w-full max-w-full px-3 mb-6 sm:w-6/12 sm:flex-none xl:mb-0 xl:w-6/12">
                        <x-choices
                            label="Select cluser"
                            wire:model.live="cluster_searchable_id"
                            :options="$clusterSearchable"
                            search-function="search"
                            debounce="300ms" {{-- Default is `250ms`--}}
                            min-chars="2" {{-- Default is `0`--}}
                            placeholder="Select cluster"
                            single
                            clearable
                            searchable>
                            @scope('item', $clusters)
                            <x-list-item :item="$clusters">
                                <x-slot:avatar>
                                    <x-icon name="o-user" class="bg-orange-100 p-2 w-8 h8 rounded-full"/>
                                </x-slot:avatar>
                                <x-slot:value>
                                    {{$clusters->code}} :: {{$clusters->name}}
                                </x-slot:value>
                            </x-list-item>
                            @endscope
                            <x-slot:append>
                                <x-button wire:click="$dispatch('AdminCluster_Create')" class="ml-2"
                                          class="btn-success rounded-s-none">
                                    Create Cluster
                                </x-button>
                            </x-slot:append>

                        </x-choices>
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3">
                    <div class="w-full max-w-full px-3 mb-6 sm:w-1/5 sm:flex-none xl:mb-0 xl:w-1/5">
                        <x-input wire:model="code" label="Code"/>
                    </div>
                    <div class="w-full max-w-full px-3 mb-6 sm:w-1/5 sm:flex-none xl:mb-0 xl:w-1/5">
                        <x-input wire:model="abbrev" label="abbreviation"/>
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3">
                    <div class="w-full max-w-full px-3 mb-6 sm:w-3/5 sm:flex-none xl:mb-0 xl:w-3/5">
                        <x-input wire:model="name" label="Name"/>
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3">
                    <div class="w-full max-w-full px-3 mb-6 sm:w-2/5 sm:flex-none xl:mb-0 xl:w-2/5">
                        <x-input wire:model="email" label="Email" email/>
                    </div>
                    <div class="w-full max-w-full px-3 mb-6 sm:w-2/5 sm:flex-none xl:mb-0 xl:w-2/5">
                        <x-input wire:model="password" label="Password" password/>
                    </div>
                </div>
                <br/>
                <div class="flex flex-wrap -mx-3">
                    <div class="w-full max-w-full px-3 mb-6 sm:w-4/4 sm:flex-none xl:mb-0 xl:w-4/4">
                        <x-button wire:click="createProgramEnable" class="btn btn-error btn-sm" icon="o-x-circle" label="Cancel"/>
                        <x-button wire:click="save" class="btn btn-success btn-sm" icon="o-bookmark" label="Save"/>
                    </div>
                </div>
            </x-card>
        </div>
    @endif
    <livewire:pages::admin.cluster.create/>
    {{-- Very little is needed to make a happy life. - Marcus Aurelius --}}
</div>
