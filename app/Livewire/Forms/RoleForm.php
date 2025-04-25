<?php

namespace App\Livewire\Forms;

use App\Models\Role;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class RoleForm extends Form
{
    public ?Role $role = null;

    public string $name = '';

    public string $slug = '';


    protected function rules(): array
    {
        return [
            'name' => ['required'],
            'slug' => [
                'required',
                Rule::unique('roles')->ignore($this->role?->id),
            ],
        ];

    }

    public function resetFields()
    {
        $this->name = '';
        $this->slug = '';
        $this->role = null;

    }

    public function setRole(?Role $role): void
    {
        $this->role = $role;
    }
}
