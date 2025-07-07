<?php

namespace App\Jobs;

use App\Mail\EmployeeImportSuccess;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendImportProcessedEmailJob implements ShouldQueue
{
    use Queueable;

    protected string $managerName;
    protected string $fileName;
    protected int $totalEmployeesImported;
    protected string $toEmail;

    /**
     * Create a new job instance.
     */
    public function __construct(string $managerName, string $fileName, int $totalEmployeesImported, string $toEmail)
    {
        $this->managerName = $managerName;
        $this->fileName = $fileName;
        $this->totalEmployeesImported = $totalEmployeesImported;
        $this->toEmail = $toEmail;
    }

    /**
     * Execute the job.
     * @throws \Exception
     */
    public function handle(): void
    {
        try {
            Mail::to($this->toEmail)->send(new EmployeeImportSuccess(
                $this->managerName,
                $this->fileName,
                $this->totalEmployeesImported
            ));
        } catch (\Exception $e) {
            Log::error('Falha ao enviar e-mail de confirmação: ' . $e->getMessage(), [
                'managerEmail' => $this->toEmail,
                'fileName'     => $this->fileName,
            ]);
            throw $e;
        }
    }
}
