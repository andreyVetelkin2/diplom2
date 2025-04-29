<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UserForm extends Form
{

    public ?User $user = null;

    #[Validate('required')]
    public string $name = '';

    public string $email = '';

    public string $password = '';

    protected function rules(): array
    {
        $rules = [
            'name' => ['required'],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->user?->id),
            ],
        ];

        if (!$this->user || !$this->user->exists) {
            $rules['password'] = ['required', 'min:6'];
        }

        return $rules;
    }

    public function resetFields()
    {
        $this->user = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';

    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }


}
