<?php

use Livewire\Component;
use App\Models\ST\Program;
use Livewire\Attributes\On;
use Livewire\WithPagination;
new class extends Component
{
    //
    use WithPagination;
    public $addData = false;
    public $headers = [
        ['key' => 'id', 'label' => '#', 'class' => 'w-1/12 hidden'],
        ['key' => 'user', 'label' => 'User', 'class' => 'w-1/12 align-top'],
        ['key' => 'email', 'label' => 'Email', 'class' => 'w-2/12 align-top'],
        ['key' => 'description', 'label' => 'Description', 'class' => 'w-3/12 align-top'],
        ['key' => 'cluster', 'label' => 'Cluster', 'class' => 'w-3/12 align-top'],
        ['key' => 'action', 'label' => 'Action', 'class' => 'w-2/12 align-top text-right'],
    ];
    public function render(){
        $programs = Program::paginate(10);
        return $this->view([
            'programs' => $programs
        ]);
    }
    #[On('clientProgramIdx_addProgram')]
    public function enableAddProgram(){
        $this->dispatch('AdminDataProgramIdx_createProgramEnable');
    }

    public function loginAs($programId){
        $program = Program::find($programId);
        $user = $program->user;
        auth()->login($user);
        return redirect()->route('program');
    }

};
?>

<div>
    <x-card title="Admin | Programer" shadow separator>
        <div class="text-right">
            <x-button wire:click="enableAddProgram" class="btn btn-success btn-sm" label="Add program" />
            <br/>
            <br/>
            <hr/>
            <br/>
        </div>
        <livewire:pages::admin.program.create/>
        {{$programs}}
        <div>
            @if($programs && $programs->count() > 0)
                <div class="w-full">
                    <x-table :headers="$headers" :rows="$programs" with-pagination>
                        {{-- Gunakan variabel unik seperti $item di scope --}}
                        @scope('cell_user', $item)
                        {{ $item->user->name ?? '-' }}
                        @endscope

                        @scope('cell_email', $item)
                        {{ $item->user->email ?? '-' }}
                        @endscope

                        @scope('cell_description', $item)
                        {{ $item->abbrev }} - {{ $item->name }}
                        @endscope

                        @scope('cell_cluster', $item)
                        @if($item->cluster && $item->cluster->base)
                            {{ $item->cluster->base->code }} | {{ $item->cluster->base->name }}
                        @endif
                        @endscope

                        @scope('cell_action', $item)
                        <div class="text-right">
                            <x-button wire:click="$dispatch('clientProgram_Create',{programId: {{ $item->id }} })" class="btn-success btn-sm" label="Edit" />
                            <x-button wire:click="loginAs({{ $item->id }})" class="btn-warning btn-sm" label="Login as" />
                        </div>
                        @endscope
                    </x-table>
                </div>
            @else
                <div class="p-5 text-center">Data program tidak ditemukan.</div>
            @endif
        </div>
    </x-card>
</div>
