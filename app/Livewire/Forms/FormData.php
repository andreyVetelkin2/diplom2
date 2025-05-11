<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Form as FormModel;
use Livewire\Attributes\Validate;

class FormData extends Form
{
    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('nullable|string')]
    public ?string $description = '';

    #[Validate('required|exists:categories,id')]
    public ?int $category_id = null;

    #[Validate('nullable|string|max:50')]
    public ?string $points = '';

    #[Validate('required|exists:form_templates,id')]
    public ?int $form_template_id = null;

    #[Validate('boolean')]
    public bool $is_active = true;


    #[Validate('required|string|max:255')]
    public string $slug = '';

    public function fillFromForm(FormModel $form): void
    {
        $this->title = $form->title;
        $this->description = $form->description;
        $this->category_id = $form->category_id;
        $this->points = $form->points;
        $this->form_template_id = $form->form_template_id;
        $this->is_active = $form->is_active;
        $this->slug = $form->slug;
    }


    public function resetFields()
    {
        $this->title = '';
        $this->description = '';
        $this->category_id = null;
        $this->points = '';
        $this->form_template_id = null;
        $this->is_active = true;
        $this->single_entry = false;
        $this->slug = '';
    }
}
