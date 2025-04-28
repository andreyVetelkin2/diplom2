<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use Livewire\Attributes\Validate;

class FormTemplateForm extends Form
{
    #[Validate('required|string|max:255')]
    public string $templateName = '';

    #[Validate('array|min:1')]
    public array $fields = [];

    protected function rules(): array
    {
        return [
            'fields.*.name'     => ['required', 'string', 'max:255'],
            'fields.*.label'    => ['required', 'string', 'max:255'],
            'fields.*.type'     => ['required', 'in:string,datetime,checkbox,list,file'],
            'fields.*.required' => ['boolean'],
            'fields.*.options'  => ['nullable', 'array'],
            'fields.*.options.*.label' => ['required_with:fields.*.options', 'string', 'max:255'],
            'fields.*.options.*.value' => ['required_with:fields.*.options', 'string', 'max:255'],
        ];
    }

    public function resetFields()
    {
        $this->templateName = '';
        $this->fields = [];
    }

    public function fillFromTemplate(array $template)
    {
        $this->templateName = $template['name'];
        $this->fields = $template['fields'];
    }
}

