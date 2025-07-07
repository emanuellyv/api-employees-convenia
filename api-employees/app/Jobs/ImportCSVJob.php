<?php

namespace App\Jobs;

use App\Models\Employee;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use League\Csv\Exception;
use League\Csv\InvalidArgument;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\UnavailableStream;

class ImportCSVJob implements ShouldQueue
{
    use Queueable;

    protected string $filePath;
    protected int $managerId;
    protected string $managerName;
    protected string $managerEmail;

    /**
     * Create a new job instance.
     */
    public function __construct(string $filePath, int $managerId, string $managerName, string $managerEmail)
    {
        $this->filePath = $filePath;
        $this->managerId = $managerId;
        $this->managerName = $managerName;
        $this->managerEmail = $managerEmail;
    }

    /**
     * Execute the job.
     * @throws UnavailableStream
     * @throws InvalidArgument
     * @throws Exception
     */
    public function handle(): void
    {
        DB::beginTransaction();

        try {
            $filePath = storage_path('app/public/' . $this->filePath);

            $csv = Reader::createFromPath($filePath);
            $csv->setDelimiter(',');
            $csv->setHeaderOffset(0);
            $records = (new Statement())->process($csv);

            $totalEmployeesImported = 0;

            foreach ($records as $record) {
                $name = $record['name'] ?? null;
                $email = $record['email'] ?? null;
                $cpf = $record['cpf'] ? Str::remove(['.', '-', '/'], $record['cpf']) : null;
                $city = $record['city'] ?? null;
                $state = $record['state'] ?? null;

                if (! $name || ! $email || ! $cpf || ! $city || ! $state) {
                    continue;
                }

                if (! filter_var($email, FILTER_VALIDATE_EMAIL) || Employee::where('email', $email)->exists()) {
                    continue;
                }

                if (strlen($cpf) !== 11 || Employee::where('cpf', $cpf)->exists()) {
                    continue;
                }

                $employeeData = [
                    'name'       => $name,
                    'email'      => $email,
                    'cpf'        => $cpf,
                    'city'       => $city,
                    'state'      => $state,
                    'manager_id' => $this->managerId,
                ];

                Employee::create($employeeData);
                $totalEmployeesImported++;
            }
            DB::commit();

            SendImportProcessedEmailJob::dispatch(
                $this->managerName,
                $this->filePath,
                $totalEmployeesImported,
                $this->managerEmail
            );
        } catch (Exception $e) {
            DB::rollBack();

            \Log::error('Erro ao importar arquivo de colaboradores' . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }
}
