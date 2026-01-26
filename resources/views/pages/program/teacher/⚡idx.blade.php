<?php


use App\Models\ST\Teacher;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    //
    use WithPagination;
    public $addData = false;
    public $headers = [
        ['key' => 'id', 'label' => '#', 'class' => 'w-1/12 hidden'],
        ['key' => 'code', 'label' => 'Code', 'class' => 'w-1/12 align-top'],
        ['key' => 'univ_code', 'label' => 'Univ code', 'class' => 'w-1/12 align-top'],
        ['key' => 'employee_id', 'label' => 'Employee ID', 'class' => 'w-2/12 align-top'],
        ['key' => 'name', 'label' => 'Name', 'class' => 'w-5/12 align-top'],
        ['key' => 'email', 'label' => 'Email', 'class' => 'w-2/12 align-top'],
        ['key' => 'action', 'label' => 'Action', 'class' => 'w-1/12 align-top text-right'],
    ];

    #[On('ProgramDataTeachersIndex_refresh')]
    public function render()
    {
        $teachers = Teacher::where('program_id', auth()->user()->program->id)->paginate(10);
        return $this->view(['teachers' => $teachers]);
    }

    public function mount(){
        if(!Auth()->user()){
            return redirect()->route('login');
        }
    }



    public function  enableAddProgram()
    {
        $this->dispatch('ProgramTeacherImportExcel_enableAddData');
    }
};
?>

<div>
    <x-card title="Program | Data :: Teacher" shadow separator>
        <div class="text-right">
            <x-button wire:click="enableAddProgram" class="btn btn-success btn-sm" label="Import teacher" />
            <br/>
            <br/>
            <hr/>
            <br/>
        </div>
        <livewire:pages::program.teacher.import-excel/>
        @if($teachers->isNotEmpty())
            <div class="w-full max-w-full px-3 mb-6 sm:w-12/12 sm:flex-none xl:mb-0 xl:w-12/12">
                <x-table :headers="$headers" :rows="$teachers" wire:model="expanded" with-pagination>
                    @scope('cell_user', $teachers)
                    @if(!is_null($teachers->user))
                        {{$teachers->user->name}}
                    @endif
                    @endscope
                    @scope('cell_email', $teachers)
                    @if(!is_null($teachers->user))
                        {{$teachers->user->email}}
                    @endif
                    @endscope
                    @scope('cell_name', $teachers)
                    {{$teachers->front_title}} {{$teachers->name}}, {{$teachers->rear_title}}
                    @endscope
                    @scope('cell_description', $teachers)
                    {{$teachers->employee_id}} {{$teachers->last_name}}
                    @endscope

                    @scope('cell_action', $teachers)
                    <x-button wire:click="$dispatch('ProgramDataTeachersEdit_editTeachers',{teacherId: {{$teachers->id}} })" icon="o-pencil" class="btn btn-success btn-sm" label="Edit" />
                    {{--<x-button wire:click="loginAs({{$teachers->id}})" class="btn btn-warning btn-sm" label="Login as" />--}}
                    @endscope
                </x-table>

            </div>
        @else
            <div class="w-full max-w-full px-3 mb-6 sm:w-12/12 sm:flex-none xl:mb-0 xl:w-12/12 text-center">
                <br/>
                There is no data
            </div>
        @endif
    </x-card>
    {{-- Act only according to that maxim whereby you can, at the same time, will that it should become a universal law. - Immanuel Kant --}}
</div>
