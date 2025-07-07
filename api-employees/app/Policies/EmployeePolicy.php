<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\Manager;

class EmployeePolicy
{
    public function viewAny(Manager $manager): bool
    {
        return true;
    }

    public function view(Manager $manager, Employee $employee): bool
    {
        return $manager->id === $employee->manager_id;
    }

    public function create(Manager $manager): bool
    {
        return true;
    }

    public function update(Manager $manager, Employee $employee): bool
    {
        return $manager->id === $employee->manager_id;
    }

    public function delete(Manager $manager, Employee $employee): bool
    {
        return $manager->id === $employee->manager_id;
    }

    public function import(Manager $manager): bool
    {
        return true;
    }
}
