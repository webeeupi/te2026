<?php

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use App\Jobs\SubjectImportJob;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    use WithFileUploads, Toast;

    public $enableAddData = false;
    public $file;

    // Trigger dari Parent Component
    #[On('ProgramSubjectImportExcel_enableAddData')]
    public function enableImportExcel() {
        $this->enableAddData = !$this->enableAddData;
    }

    public function updatedFile()
    {
        $this->validate(['file' => 'required|mimes:xlsx,csv|max:10240']);

        try {
            // 1. Ambil ID Program Studi User yang Login
            $programId = Auth::user()->program?->id;

            if (!$programId) {
                $this->error('User tidak terhubung dengan Program Studi manapun.');
                return;
            }

            // 2. Simpan file sementara
            $name = $this->file->hashName();
            $this->file->storeAs('temp-imports', $name, 'local');
            $fullPath = storage_path('app/private/temp-imports/' . $name);

            // 3. Dispatch Job dengan menyertakan Program ID
            SubjectImportJob::dispatch($fullPath, $programId);

            $this->info('File Mata Kuliah sedang diproses...');
            $this->reset('file');

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    // Listener Real-time
    #[On('echo:subjectImport,.SubjectImportEvent')]
    public function handleImportEvent($payload)
    {
        if($payload['status'] == "success") {
            $this->success($payload['message'], position: 'toast-top toast-center');
            $this->enableAddData = false; // Tutup modal
        } else {
            $this->error($payload['message'], position: 'toast-top toast-center');
        }

        // Refresh tabel di component utama
        $this->dispatch('ProgramDataSubjectsIndex_refresh');
    }
};
?>

<div>
    @if($enableAddData)
        <x-card class="bg-gray-50" subtitle="Import Data Mata Kuliah" shadow separator>
            <div class="flex flex-wrap -mx-3">
                <div class="w-full max-w-full px-3 mb-6 sm:w-3/5 sm:flex-none xl:mb-0 xl:w-3/5">
                    {{-- Input File --}}
                    <x-file
                        wire:model.live="file"
                        label="Upload File Excel (Subject)"
                        hint="Kolom: code, name, credit, semester, curriculum"
                        spinner
                        accept=".xlsx, .csv"
                    />
                </div>

                {{-- Tombol Batal --}}
                <div class="w-full max-w-full py-10 px-3 mb-6 sm:w-1/5 sm:flex-none xl:mb-0 xl:w-1/5">
                    <x-button wire:click="enableImportExcel" class="btn btn-error btn-sm text-white" label="Batal" />
                </div>
            </div>
        </x-card>
    @endif
</div>
