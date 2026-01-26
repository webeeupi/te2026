<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Jobs\ScheduleImportJob;
use Livewire\Attributes\On;
use Mary\Traits\Toast;

new class extends Component {
    use WithFileUploads, Toast;

    public $enableAddData = false;
    public $file;

    #[On('ProgramScheduleImportExcel_enableAddData')]
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

            $this->file->storeAs('temp-schedule', $name, 'local');
            $fullPath = storage_path('app/private/temp-schedule/' . $name);

            ScheduleImportJob::dispatch($fullPath, $programId);

            $this->info('Jadwal sedang diimport...');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }


    #[On('echo:scheduleImport,.ScheduleImportEvent')]
    public function handleEvent($payload)
    {
        $this->file = null;
        if($payload['status'] == "success") {

            $this->success($payload['message'], position: 'toast-top toast-center');
        } else {
            $this->error($payload['message'], position: 'toast-top toast-center');
        }
        $this->dispatch('ProgramScheduleIndex_refresh');
    }
}; ?>

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
