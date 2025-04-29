<?php

namespace App\Livewire\Forms;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Validation\Rule;
use Livewire\Form;

class PermissionForm extends Form
{
    public ?Permission $permission = null;

    public string $name = '';

    public string $slug = '';


    protected function rules(): array
    {
        return [
            'name' => ['required'],
            'slug' => [
                'required',
                Rule::unique('permissions')->ignore($this->permission?->id),
            ],
        ];

    }

    public function resetFields()
    {
        $this->name = '';
        $this->slug = '';
        $this->permission = null;

    }

    public function setPermission(?Permission $permission): void
    {
        $this->permission = $permission;
    }
}
