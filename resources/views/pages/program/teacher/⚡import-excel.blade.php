<?php

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use App\Jobs\TeacherImportJob;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads, Toast;

    public $enableAddData = false;
    public $file;

    #[On('ProgramTeacherImportExcel_enableAddData')]
    public function enableImportExcel() {
        $this->enableAddData = !$this->enableAddData;
    }

    public function updatedFile()
    {
        $this->validate(['file' => 'required|mimes:xlsx|max:10240']);

        try {
            $programId = auth()->user()->program->id;

            // Gunakan storeAs untuk kontrol penuh lokasi file
            $name = $this->file->hashName();

            // Simpan ke 'temp-imports' (ini akan masuk ke storage/app/private/temp-imports jika menggunakan disk local default)
            // ATAU kita paksa simpan agar sesuai dengan folder di screenshot Anda:
            $this->file->storeAs('temp-imports', $name, 'local');

            // Sesuaikan path berdasarkan config 'local' Anda di image_500820.png
            // Karena root local Anda adalah 'storage/app/private'
            $fullPath = storage_path('app/private/temp-imports/' . $name);

            TeacherImportJob::dispatch($fullPath, $programId);

            $this->info('File diterima, sedang diproses...');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    // Listener Real-time dari Reverb
    #[On('echo:teacherImport,.TeacherImportEvent')]
    public function handleImportEvent($payload)
    {
        //\Illuminate\Support\Facades\File::delete($this->path);
        $this->file = null;
        if($payload['status'] == "success") {
            $this->success($payload['message'], position: 'toast-top toast-center');
        } else {
            $this->error($payload['message'], position: 'toast-top toast-center');
        }
        $this->dispatch('ProgramDataTeachersIndex_refresh');
    }
};
?>

<div>
    @if($enableAddData)
        <x-card class="bg-gray-50" subtitle="Import Excel Data" shadow separator>
            <div class="flex flex-wrap -mx-3">
                <div class="w-full max-w-full px-3 mb-6 sm:w-3/5 sm:flex-none xl:mb-0 xl:w-3/5">
                    <x-file wire:model.live="file" label="Upload File Excel" hint="Pastikan format .xlsx" spinner accept=".xlsx" />
                </div>
                <div class="w-full max-w-full py-10 px-3 mb-6 sm:w-1/5 sm:flex-none xl:mb-0 xl:w-1/5">
                    <x-button wire:click="enableImportExcel" class="btn btn-outline btn-sm" label="Batal" />
                </div>
            </div>
        </x-card>
    @endif
</div>
