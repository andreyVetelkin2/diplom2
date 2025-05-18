<?php
namespace App\Livewire\Forms;

use App\Models\Position;
use Livewire\Form;
use App\Models\Institute;
use Illuminate\Validation\Rule;

class PositionForm extends Form
{
    public ?Position $position = null;

    public string $name = '';

    public function setPosition(Position $position): void
    {
        $this->position = $position;
        $this->name = $position->name;
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
