<?php

namespace App\Livewire;


use Livewire\Component;
use App\Models\FormTemplate;
use App\Models\FormEntry;
use App\Models\FieldEntryValue;

class Form extends Component
{
    public $templates;
    public $selectedTemplate = null;
    public $fields = [];
    public $formData = [];

    public function mount()
    {
        // Загружаем все доступные шаблоны форм
        $this->templates = FormTemplate::all();
    }

    public function updatedSelectedTemplate($value)
    {
        if ($value) {
            // При выборе шаблона загружаем его поля и опции
            $template = FormTemplate::with('fields.options')
                ->find($value);

            $this->fields = $template
                ->fields
                ->sortBy('sort_order')
                ->map(function($field) {
                    return [
                        'id'       => $field->id,
                        'name'     => $field->name,
                        'label'    => $field->label,
                        'type'     => $field->type,
                        'required' => $field->required,
                        'options'  => $field->options->sortBy('sort_order')->toArray(),
                    ];
                })
                ->toArray();

            // Сброс введённых данных
            $this->formData = [];
        } else {
            $this->fields = [];
        }
    }

    public function submit()
    {
        // Правила валидации по обязательности полей
        $rules = [];
        foreach ($this->fields as $field) {
            if ($field['required']) {
                $rules['formData.' . $field['name']] = 'required';
            }
        }

        $this->validate($rules);

        // Сохраняем запись заполненной формы
        $entry = FormEntry::create([
            'form_template_id' => $this->selectedTemplate,
            'user_id'          => auth()->user()->id
        ]);

        // Сохраняем значения полей
        foreach ($this->fields as $field) {
            FieldEntryValue::create([
                'form_entry_id'     => $entry->id,
                'template_field_id' => $field['id'],
                'value'             => $this->formData[$field['name']] ?? null,
            ]);
        }

        session()->flash('message', 'Форма успешно отправлена.');

        // Сброс компонентов
        $this->selectedTemplate = null;
        $this->fields = [];
        $this->formData = [];
    }

    public function render()
    {
        return view('livewire.form');
    }
}
