<?php

namespace App\Jobs;

use App\Events\PrintAllAssignmentEvent;
use App\Models\ST\Program;
use App\Models\ST\Teacher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Support\Str;

class PrintAllAssignmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $programId;
    protected $userId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $programId, int $userId)
    {
        $this->programId = $programId;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $program = Program::find($this->programId);
            if (!$program) {
                throw new \Exception("Program with ID {$this->programId} not found.");
            }

            $teachers = Teacher::with([
                'schedules' => fn($query) => $query->where('program_id', $this->programId)
            ])
            ->whereHas('schedules', fn($query) => $query->where('program_id', $this->programId))
            ->orderBy('name')
            ->get();

            if ($teachers->isEmpty()) {
                event(new PrintAllAssignmentEvent('error', 'No schedule data to print.', null, $this->userId));
                return;
            }

            $programSlug = Str::slug($program->name);
            $fileName = "all-assignments-{$programSlug}.pdf";
            $relativePath = 'public/temp-pdf/' . $fileName;
            $fullPath = storage_path('app/' . $relativePath);

            Storage::makeDirectory('public/temp-pdf');

            Pdf::view('pdf.program.assignment.print-multiple', ['teachers' => $teachers])
                ->paperSize(330, 210, 'mm')
                ->margins(20, 10, 20, 10)
                ->footerView('pdf.footer')
                ->save($fullPath);

            $downloadUrl = Storage::url('temp-pdf/' . $fileName);

            event(new PrintAllAssignmentEvent('success', 'PDF for all assignments has been generated!', $downloadUrl, $this->userId));

        } catch (\Exception $e) {
            event(new PrintAllAssignmentEvent('error', 'Failed to generate PDF: ' . $e->getMessage(), null, $this->userId));
        }
    }
}
