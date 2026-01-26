<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\ST\Subject;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    use WithPagination;

    // Header tabel disesuaikan dengan field Database
    public $headers = [
        ['key' => 'id', 'label' => '#', 'class' => 'w-1/12 hidden'],
        ['key' => 'semester', 'label' => 'Semester', 'class' => 'w-1/12 align-top text-center'],
        ['key' => 'credit', 'label' => 'Credit', 'class' => 'w-1/12 align-top text-center'],
        ['key' => 'code', 'label' => 'Code', 'class' => 'w-1/12 align-top font-bold'],
        ['key' => 'name', 'label' => 'Name', 'class' => 'w-4/12 align-top'],
        ['key' => 'curriculum', 'label' => 'Curriculum', 'class' => 'w-2/12 align-top text-center'],
        ['key' => 'action', 'label' => 'Action', 'class' => 'w-1/12 align-top text-right'],
    ];

    public function mount()
    {
        if(!Auth::user()){
            return redirect()->route('login');
        }
    }

    #[On('ProgramDataSubjectsIndex_refresh')]
    public function render()
    {
        // Gunakan safety check (?->) agar tidak error jika user admin/superadmin tanpa prodi login
        $programId = Auth::user()->program?->id ?? 0;

        $subjects = Subject::query()
            ->where('program_id', $programId)
            ->orderBy('semester', 'asc') // Urutkan semester 1, 2, 3...
            ->orderBy('code', 'asc')     // Lalu urutkan kode
            ->paginate(10);

        return $this->view([ 'subjects' => $subjects ]);
    }

    public function enableAddsubject()
    {
        // Buka modal di component child
        $this->dispatch('ProgramSubjectImportExcel_enableAddData');
    }

    // Opsional: Fungsi delete
    public function delete($id)
    {
        Subject::find($id)?->delete();
        $this->success('Data berhasil dihapus');
    }
};
?>

<div>
    <x-card title="Program | Data :: Subjects" shadow separator>

        <livewire:pages::program.subject.import-excel/>
        {{-- Bagian Header & Tombol --}}
        <div class="flex justify-end mb-4">
            <x-button
                wire:click="enableAddsubject"
                class="btn btn-success btn-sm text-white"
                label="Import Subject"
                icon="o-arrow-up-tray"
            />
        </div>

        @if($subjects->isNotEmpty())
            <div class="w-full max-w-full px-3 mb-6 sm:w-12/12 sm:flex-none xl:mb-0 xl:w-12/12">

                <x-table :headers="$headers" :rows="$subjects" wire:model="expanded" with-pagination>

                    {{--
                        Scope untuk Custom Styling Kolom.
                        Kolom 'semester', 'name', dll tidak perlu scope jika isinya text biasa.
                    --}}

                    {{-- Contoh: Menebalkan Kode --}}
                    @scope('cell_code', $subject)
                    <span class="font-bold text-slate-700">{{ $subject->code }}</span>
                    @endscope

                    {{-- Scope Action --}}
                    @scope('cell_action', $subject)
                    <div class="flex justify-end gap-1">
                        {{-- Tombol Edit --}}
                        <x-button
                            wire:click="$dispatch('ProgramDataSubjectsEdit_editSubjects', { subjectId: {{ $subject->id }} })"
                            icon="o-pencil"
                            class="btn-warning btn-sm btn-outline btn-square"
                            tooltip="Edit"
                        />

                        {{-- Tombol Hapus (Opsional) --}}
                        <x-button
                            wire:click="delete({{ $subject->id }})"
                            wire:confirm="Yakin ingin menghapus {{ $subject->name }}?"
                            icon="o-trash"
                            class="btn-error btn-sm btn-outline btn-square"
                            tooltip="Hapus"
                        />
                    </div>
                    @endscope

                </x-table>

            </div>
        @else
            {{-- Tampilan Kosong --}}
            <div class="w-full text-center py-10">
                <x-icon name="o-archive-box-x-mark" class="w-12 h-12 mx-auto text-gray-300 mb-3"/>
                <span class="text-gray-500">Belum ada data mata kuliah. Silakan import data.</span>
            </div>
        @endif

        {{-- Panggil Component Import di sini --}}


    </x-card>
</div>
