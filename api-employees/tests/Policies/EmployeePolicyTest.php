<?php

namespace Policies;

use App\Models\Employee;
use App\Models\Manager;
use App\Policies\EmployeePolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeePolicyTest extends TestCase
{
    use RefreshDatabase;

    protected EmployeePolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new EmployeePolicy();
    }

    public function test_view_any_returns_true()
    {
        $manager = Manager::factory()->create();

        $this->assertTrue($this->policy->viewAny($manager));
    }

    public function test_view_returns_true_if_manager_owns_employee()
    {
        $manager = Manager::factory()->create();
        $employee = Employee::factory()->create(['manager_id' => $manager->id]);

        $this->assertTrue($this->policy->view($manager, $employee));
    }

    public function test_view_returns_false_if_manager_does_not_own_employee()
    {
        $manager = Manager::factory()->create();
        $otherManager = Manager::factory()->create();
        $employee = Employee::factory()->create(['manager_id' => $otherManager->id]);

        $this->assertFalse($this->policy->view($manager, $employee));
    }

    public function test_create_returns_true()
    {
        $manager = Manager::factory()->create();

        $this->assertTrue($this->policy->create($manager));
    }

    public function test_update_returns_true_if_manager_owns_employee()
    {
        $manager = Manager::factory()->create();
        $employee = Employee::factory()->create(['manager_id' => $manager->id]);

        $this->assertTrue($this->policy->update($manager, $employee));
    }

    public function test_update_returns_false_if_manager_does_not_own_employee()
    {
        $manager = Manager::factory()->create();
        $otherManager = Manager::factory()->create();
        $employee = Employee::factory()->create(['manager_id' => $otherManager->id]);

        $this->assertFalse($this->policy->update($manager, $employee));
    }

    public function test_delete_returns_true_if_manager_owns_employee()
    {
        $manager = Manager::factory()->create();
        $employee = Employee::factory()->create(['manager_id' => $manager->id]);

        $this->assertTrue($this->policy->delete($manager, $employee));
    }

    public function test_delete_returns_false_if_manager_does_not_own_employee()
    {
        $manager = Manager::factory()->create();
        $otherManager = Manager::factory()->create();
        $employee = Employee::factory()->create(['manager_id' => $otherManager->id]);

        $this->assertFalse($this->policy->delete($manager, $employee));
    }

    public function test_import_returns_true()
    {
        $manager = Manager::factory()->create();

        $this->assertTrue($this->policy->import($manager));
    }
}
