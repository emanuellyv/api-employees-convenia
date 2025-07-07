<?php

namespace Tests\Feature;

use App\Jobs\ImportCSVJob;
use App\Models\Employee;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EmployeeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticateManager(): Manager
    {
        $manager = Manager::factory()->create();
        $this->actingAs($manager, 'manager');

        return $manager;
    }

    public function test_index_employees()
    {
        $manager = Manager::factory()->create();
        $this->actingAs($manager, 'manager');

        Employee::factory()->count(2)->create(['manager_id' => $manager->id]);

        $response = $this->json('GET', '/api/employees');
        $response->assertStatus(200)->assertJsonCount(2);
    }

    public function test_store_employee(): void
    {
        $manager = Manager::factory()->create();

        $payload = [
            'name'       => 'Emanuelly Valenga',
            'email'      => 'emanuellyvalenga.dev@gmail.com',
            'cpf'        => '12345678910',
            'city'       => 'Curitiba',
            'state'      => 'Parana',
            'manager_id' => '2',
        ];

        $response = $this->actingAs($manager, 'manager')->json('POST', '/api/employees', $payload);

        $response->assertStatus(201)->assertJsonFragment([
            'name'  => $payload['name'],
            'email' => $payload['email'],
        ]);

        $this->assertDatabaseHas('employees', ['email' => $payload['email']]);
    }

    public function test_show_employee()
    {
        $manager = Manager::factory()->create();
        $this->actingAs($manager, 'manager');

        $employee = Employee::factory()->create(['manager_id' => $manager->id]);

        $response = $this->json('GET', "/api/employees/{$employee->id}");

        $response->assertStatus(200)->assertJsonFragment([
            'name' => $employee->name,
        ]);
    }

    public function test_update_employee()
    {
        $manager = Manager::factory()->create();
        $this->actingAs($manager, 'manager');

        $employee = Employee::factory()->create(['manager_id' => $manager->id]);

        $payload = [
            'name'  => 'Emanuelly Valenga Dias',
            'email' => 'emanuelly@convenia.com',
            'cpf'   => '12345678910',
            'city'  => 'Curitiba',
            'state' => 'Parana',
        ];

        $response = $this->json('PUT', "/api/employees/{$employee->id}", $payload);

        $response->assertStatus(200)->assertJsonFragment([
            'name'  => $payload['name'],
            'email' => $payload['email'],
        ]);

        $this->assertDatabaseHas('employees', [
            'id'    => $employee->id,
            'name'  => $payload['name'],
            'email' => $payload['email'],
        ]);
    }

    public function test_remove_employee()
    {
        $manager = Manager::factory()->create();
        $this->actingAs($manager, 'manager');

        $employee = Employee::factory()->create(['manager_id' => $manager->id]);

        $response = $this->json('DELETE', "/api/employees/{$employee->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('employees', [
            'id' => $employee->id,
        ]);
    }

    public function test_import_employees()
    {
        $manager = Manager::factory()->create();
        $this->actingAs($manager, 'manager');

        Storage::fake('local');
        Queue::fake();

        $file = UploadedFile::fake()->create('file_test.csv', 10, 'text/csv');

        $response = $this->json('POST', '/api/employees/import', [
            'file' => $file,
        ]);

        $response->assertStatus(201)->assertJsonFragment([
            'status'  => true,
            'message' => 'Importação iniciada com sucesso.',
        ]);

        Queue::assertPushed(ImportCSVJob::class, function ($job) use ($manager) {
            return $job->manager === $manager->id;
        });
    }
}
