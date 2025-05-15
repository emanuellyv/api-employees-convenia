<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmployeeImportSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public string $managerName;
    public string $fileName;
    public int $totalEmployeesImported;

    /**
     * Create a new message instance.
     * @param $managerName, $fileName, $totalEmployeesImported
     */
    public function __construct(string $managerName, string $fileName, int $totalEmployeesImported)
    {
        $this->managerName = $managerName;
        $this->fileName = $fileName;
        $this->totalEmployeesImported = $totalEmployeesImported;
    }

    public function build(): EmployeeImportSuccess
    {
        return $this->subject('Importação de Colaboradores Concluída')
            ->text('emails.employee-import-success-text');
    }
}
