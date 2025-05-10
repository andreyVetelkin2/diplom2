<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Form as FormModel;
use Livewire\Attributes\Validate;

class FormData extends Form
{
    #[Validate('required|string|max:255')]
    public string $title = '';

    public ?string $description = '';
    public ?int $category_id = null;
    public ?string $points = '';
    public ?int $form_template_id = null;
    public bool $is_active = true;
    public bool $single_entry = false;
    public string $slug = '';

    protected function rules(): array
    {
        return [
            'title'             => 'required|string|max:255',
            'slug'             => 'required|string|max:255',
            'description'       => 'nullable|string',
            'category_id'       => 'required|exists:categories,id',
            'points'            => 'nullable|string|max:50',
            'form_template_id'  => 'required|exists:form_templates,id',
            'is_active'         => 'boolean',
            'single_entry'      => 'boolean',
        ];
    }

    public function fillFromForm(FormModel $form): void
    {
        $this->title = $form->title;
        $this->description = $form->description;
        $this->category_id = $form->category_id;
        $this->points = $form->points;
        $this->form_template_id = $form->form_template_id;
        $this->is_active = $form->is_active;
        $this->single_entry = $form->single_entry;
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
