<?php

use App\Models\Employee;
use App\Traits\EmployeeTraits;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;

class EmployeeTraitsTest extends TestCase
{
    use RefreshDatabase, EmployeeTraits;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testGetEmployeeById()
    {
        $employee = Employee::factory()->create();

        $employeeFromTrait = $this->getEmployeeById($employee->id);

        assertInstanceOf(Employee::class, $employeeFromTrait);
        assertEquals($employee->id, $employeeFromTrait->id);
    }

    public function testThrowsErrorWhenEmployeeNotFound()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Employee not found');
        $this->expectExceptionCode(404);

        $this->getEmployeeById('non_existing_id', true);
    }

    public function testDoesNotThrowErrorWhenEmployeeNotFound()
    {
        $employee = $this->getEmployeeById('non_existing_id', false);

        assertEquals(null, $employee);
    }

    public function testGetFirstName()
    {
        $names = "Sheja Eddy";

        $firstName = $this->getFirstName($names);

        assertEquals("Sheja", $firstName);
    }

    public function testGetFirstNameWithEmptyNames()
    {
        $names = "";

        $firstName = $this->getFirstName($names);

        assertEquals("Unknown", $firstName);
    }

    public function testGetFirstNameWithOneName()
    {
        $names = "Sheja";

        $firstName = $this->getFirstName($names);

        assertEquals("Sheja", $firstName);
    }
}
