<?php

use App\Jobs\PrintAllAssignmentJob;
use App\Jobs\PrintAssignmentJob;
use Livewire\Component;
use App\Models\ST\Schedule;
use App\Models\ST\Teacher;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Mary\Traits\Toast;

new class extends Component
{
    use WithPagination, Toast;

    public $teachersSearchable = null;
    public $teacher_searchable_id = null;
    public $programId;
    public $headers = [
        ['key' => 'id', 'label' => '#', 'class' => 'w-1/12 hidden'],
        ['key' => 'day', 'label' => 'Hari', 'class' => 'w-1/12 align-top'],
        ['key' => 'hour', 'label' => 'Jam', 'class' => 'w-2/12 align-top'],
        ['key' => 'room', 'label' => 'Ruangan', 'class' => 'w-2/12 align-top'],
        ['key' => 'student', 'label' => 'Kelas', 'class' => 'w-1/12 align-top'],
        ['key' => 'activity', 'label' => 'Kuliah', 'class' => 'w-5/12 align-top'],
    ];

    #[On('echo:print-channel,.print.assignment.generated')]
    public function handlePrintAssignmentGenerated($data)
    {
        if ($data['userId'] !== auth()->id()) {
            return;
        }

        if ($data['status'] === 'success') {
            $this->success(
                "{$data['message']}<br><a href='{$data['fileUrl']}' target='_blank' class='btn btn-sm btn-success mt-2'>Download here</a>",
                position: 'toast-top toast-center',
                timeout: 100000,
            );
        } else {
            $this->error($data['message'], 'PDF Generation Failed', position: 'toast-top toast-center', timeout: 20000);
        }
    }

    #[On('echo:print-channel,.print.all-assignments.generated')]
    public function handlePrintAllAssignmentsGenerated($data)
    {
        if ($data['userId'] !== auth()->id()) {
            return;
        }

        if ($data['status'] === 'success') {
            $this->success(
                "{$data['message']}<br><a href='{$data['fileUrl']}' target='_blank' class='btn btn-sm btn-success mt-2'>Download here</a>",
                position: 'toast-top toast-center',
                timeout: 100000,
            );
        } else {
            $this->error($data['message'], 'PDF Generation Failed', position: 'toast-top toast-center', timeout: 20000);
        }
    }

    public function startPrintAssignment()
    {
        if (!$this->teacher_searchable_id) {
            $this->warning('Please select a teacher first.');
            return;
        }

        $programId = Auth::user()->program?->id ?? 0;
        $teacherIds = [$this->teacher_searchable_id];

        PrintAssignmentJob::dispatch($teacherIds, $programId, auth()->id());
        $this->info('Generating PDF...', 'The process is running in the background.', position: 'toast-top toast-right');
    }

    public function startAllAssignmentsPdfGeneration()
    {
        $programId = Auth::user()->program?->id ?? 0;

        PrintAllAssignmentJob::dispatch($programId, auth()->id());
        $this->info('Generating All Assignments PDF...', 'The process is running in the background.', position: 'toast-top toast-right');
    }


    public function render()
    {
        $programId = Auth::user()->program?->id;

        $schedules = Schedule::query()
            ->with(['program', 'teachers'])
            ->whereHas('program', function ($query) use ($programId) {
                $query->where('id', $programId);
                })
            ->whereHas('teachers', function ($query) {
                $query->where('teacher_id', $this->teacher_searchable_id);
            })
            ->orderBy('day','DESC')
            ->paginate(5);


        return $this->view(['schedules' => $schedules]);
    }

    public function searchTeachers(string $value = '')
    {
        $selectedOption = Teacher::where('id', $this->teacher_searchable_id)->get();
        $programId = Auth::user()->program?->id ?? 0;

        $this->teachersSearchable = Teacher::query()
            ->with(['schedules.program'])
            ->whereHas('schedules', function ($query) use ($programId) {
                $query->whereHas('program', function ($q2) use ($programId) {
                    $q2->where('id', $programId);
                });
            })
            ->where(function($q) use ($value) {
                $q->where('name', 'like', "%$value%");
            })
            ->take(5)
            ->get()
            ->merge($selectedOption);

    }

    public function mount(){
        $this->searchTeachers();
    }

    public function updatedTeacherSearchableId()
    {
        $this->resetPage();
    }
};
?>

<div>
    <x-card title="Program | Data :: Assignment" shadow separator>
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mb-6 sm:w-4/12 sm:flex-none xl:mb-0 xl:w-4/12">
                <x-choices
                    label="Select Teacher"
                    wire:model.live="teacher_searchable_id"
                    :options="$teachersSearchable"
                    search-function="searchTeachers"
                    debounce="300ms"
                    min-chars="2"
                    placeholder="Search teacher name..."
                    single
                    clearable
                    searchable>
                    @scope('item', $teacher)
                    <x-list-item :item="$teacher" sub-value="{{ $teacher->code ?? $teacher->front_tittle }}">
                        <x-slot:avatar>
                            <x-icon name="o-academic-cap" class="bg-blue-100 p-2 w-8 h-8 rounded-full"/>
                        </x-slot:avatar>
                        <x-slot:value>
                            {{ $teacher->name }}
                        </x-slot:value>
                    </x-list-item>
                    @endscope
                </x-choices>
            </div>

            <div class="w-full max-w-full px-3 py-10 mb-0 sm:w-8/12 sm:flex-none xl:mb-0 xl:w-8/12 flex items-center space-x-2">
                @if($this->teacher_searchable_id)
                    {{-- Tombol untuk print satu dosen (background) --}}
                    <x-button
                        wire:click="startPrintAssignment"
                        label="Print Teacher Assignment"
                        class="btn-primary btn-sm"
                        spinner="startPrintAssignment" />
                @endif

                {{-- Tombol untuk print semua dosen (background) --}}
                <x-button
                    wire:click="startAllAssignmentsPdfGeneration"
                    label="Print All Assignment"
                    class="btn-info btn-sm"
                    spinner="startAllAssignmentsPdfGeneration" />
            </div>
        </div>
        @if($schedules->isNotEmpty())
            <div class="w-full max-w-full px-3 mb-6 sm:w-12/12 sm:flex-none xl:mb-0 xl:w-12/12">
                <x-table :headers="$headers" :rows="$schedules" wire:model="expanded"  with-pagination>
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
                    {{$schedules->code}}-{{$schedules->credit}} SKS
                    <br/>
                    {{$schedules->subject}}
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
</div>
