<?php
namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Institute;
use Illuminate\Validation\Rule;

class InstituteForm extends Form
{
    public ?Institute $institute = null;

    public string $name = '';

    public function setInstitute(Institute $institute): void
    {
        $this->institute = $institute;
        $this->name = $institute->name;
    }

    public function resetFields(): void
    {
        $this->reset(['name']);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
