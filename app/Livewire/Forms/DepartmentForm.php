<?php
namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Department;
use Illuminate\Validation\Rule;

class DepartmentForm extends Form
{
    public ?Department $department = null;

    public string $name = '';
    public int|string|null $institute_id = null;

    public function setDepartment(Department $department): void
    {
        $this->department = $department;
        $this->name = $department->name;
        $this->institute_id = $department->institute_id;
    }

    public function resetFields(): void
    {
        $this->reset(['name', 'institute_id']);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'institute_id' => ['required', 'integer', Rule::exists('institutes', 'id')],
        ];
    }
}
