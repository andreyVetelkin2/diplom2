<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\FormTemplate;
use Illuminate\Support\Arr;

class ManageTemplates extends Component
{
    public $templates;
    public $selectedTemplateId;
    public $templateName = '';
    public $fields = [];

    protected $rules = [
        'templateName'        => 'required|string|max:255',
        'fields.*.name'       => 'required|string|max:255',
        'fields.*.label'      => 'required|string|max:255',
        'fields.*.type'       => 'required|in:string,datetime,checkbox,list',
        'fields.*.required'   => 'boolean',
        'fields.*.options'    => 'nullable|array',
        'fields.*.options.*.label' => 'required_with:fields.*.options|string|max:255',
        'fields.*.options.*.value' => 'required_with:fields.*.options|string|max:255',
    ];

    public function mount()
    {
        $this->loadTemplates();
    }

    public function loadTemplates()
    {
        $this->templates = FormTemplate::with('fields.options')->get();
    }

    public function selectTemplate($id)
    {
        $template = FormTemplate::with('fields.options')->findOrFail($id);
        $this->selectedTemplateId = $template->id;
        $this->templateName = $template->name;

        $this->fields = $template->fields->map(function ($field) {
            return [
                'id'       => $field->id,
                'name'     => $field->name,
                'label'    => $field->label,
                'type'     => $field->type,
                'required' => (bool)$field->required,
                'options'  => $field->type === 'list'
                    ? $field->options->map(function($opt) {
                        return ['label' => $opt->label, 'value' => $opt->value];
                    })->toArray()
                    : [],
            ];
        })->toArray();
    }

    public function newTemplate()
    {
        $this->reset(['selectedTemplateId', 'templateName', 'fields']);
    }

    public function addField()
    {
        $this->fields[] = [
            'name'     => '',
            'label'    => '',
            'type'     => 'string',
            'required' => false,
            'options'  => [],
        ];
    }

    public function removeField($index)
    {
        Arr::forget($this->fields, $index);
        $this->fields = array_values($this->fields);
    }

    public function addOption($fieldIndex)
    {
        $this->fields[$fieldIndex]['options'][] = ['label' => '', 'value' => ''];
    }

    public function removeOption($fieldIndex, $optIndex)
    {
        Arr::forget($this->fields[$fieldIndex]['options'], $optIndex);
        $this->fields[$fieldIndex]['options'] = array_values($this->fields[$fieldIndex]['options']);
    }

    public function saveTemplate()
    {
        $this->validate();

        $template = $this->selectedTemplateId
            ? FormTemplate::find($this->selectedTemplateId)
            : new FormTemplate();

        $template->name = $this->templateName;
        $template->save();

        // Sync fields
        $template->fields()->delete();

        foreach ($this->fields as $fieldData) {
            $field = $template->fields()->create([
                'name'     => $fieldData['name'],
                'label'    => $fieldData['label'],
                'type'     => $fieldData['type'],
                'required' => $fieldData['required'],
            ]);

            if ($fieldData['type'] === 'list' && !empty($fieldData['options'])) {
                foreach ($fieldData['options'] as $option) {
                    $field->options()->create($option);
                }
            }
        }

        session()->flash('message', 'Шаблон успешно сохранен.');
        $this->loadTemplates();
    }

    public function render()
    {
        return view('livewire.manage-templates');
    }
}

