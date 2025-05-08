<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UserForm extends Form
{
    public ?User $user = null;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public int|string|null $department_id = null;

    public function setUser(User $user): void
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->department_id = $user->department_id;
    }

    public function resetFields(): void
    {
        $this->reset(['name', 'email', 'password', 'department_id']);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'password' => [$this->user ? 'nullable' : 'required', 'string', 'min:6'],
            'department_id' => ['nullable', 'integer', Rule::exists('departments', 'id')],
        ];
    }

    public function validated(): array
    {
        $data = $this->validate();
        if (!$this->user && isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        return $data;
    }
}

