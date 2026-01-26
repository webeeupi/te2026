<?php


use App\Models\ST\Schedule;
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
        ['key' => 'day', 'label' => 'Hari', 'class' => 'w-1/12 align-top'],
        ['key' => 'hour', 'label' => 'Jam', 'class' => 'w-1/12 align-top'],
        ['key' => 'room', 'label' => 'Ruangan', 'class' => 'w-2/12 align-top'],
        ['key' => 'student', 'label' => 'Kelas', 'class' => 'w-2/12 align-top'],
        ['key' => 'activity', 'label' => 'Kuliah', 'class' => 'w-5/12 align-top'],
        ['key' => 'lecturer', 'label' => 'Dosen', 'class' => 'w-2/12 align-top'],
        ['key' => 'action', 'label' => 'Action', 'class' => 'w-1/12 align-top text-right'],
    ];

    #[On('ProgramScheduleIndex_refresh')]
    public function render()
    {
        $schedules = Schedule::where('program_id', auth()->user()->program->id)->orderBy('day', 'DESC')->paginate(10);
        return $this->view(['schedules' => $schedules]);
    }

    public function mount(){
        if(!Auth()->user()){
            return redirect()->route('login');
        }
    }



    public function  enableAddSchedule()
    {
        $this->dispatch('ProgramScheduleImportExcel_enableAddData');
    }
};
?>

<div>
    <x-card title="Program | Data :: Schedule" shadow separator>
        <div class="text-right">
            <x-button wire:click="enableAddSchedule" class="btn btn-success btn-sm" label="Import schedule" />
            <br/>
            <br/>
            <hr/>
            <br/>
        </div>
        <livewire:pages::program.schedule.import-excel/>
        @if($schedules->isNotEmpty())
            <div class="w-full max-w-full px-3 mb-6 sm:w-12/12 sm:flex-none xl:mb-0 xl:w-12/12">
                <x-table :headers="$headers" :rows="$schedules" wire:model="expanded" with-pagination>
                    @scope('cell_day', $schedules)
                        {{$schedules->day}}
                    @endscope
                    @scope('cell_hour', $schedules)
                        {{$schedules->start}}-{{$schedules->end}}
                    @endscope
                    @scope('cell_room', $schedules)
                        {{$schedules->room}}
                    @endscope
                    @scope('cell_student', $schedules)
                        {{$schedules->student}}
                    @endscope
                    @scope('cell_activity', $schedules)
                        @if(!is_null($schedules->subject))
                            {{$schedules->subject->code}}-{{$schedules->subject->credit}} SKS
                            <br/>
                            {{$schedules->subject->name}}
                       @endif
                    @endscope
                    @scope('cell_lecturer', $schedules)
                    @foreach($schedules->teachers as $teacher)
                        {{$teacher->code}}
                    @endforeach
                    @endscope
                    @scope('cell_action', $schedules)
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
