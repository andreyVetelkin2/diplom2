<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Form;
use App\Models\FormEntry;
use App\Models\FieldEntryValue;
use Illuminate\Support\Collection;

class UserFillForm extends Component
{
    public Collection $categories;
    public ?Form $selectedForm = null;
    public Collection $templateFields;
    public array $fieldValues = [];

    protected function rules(): array
    {
        $rules = [];
        foreach ($this->templateFields ?? [] as $field) {
            $key = "fieldValues.{$field->id}";
            if ($field->required) {
                $rules[$key] = 'required';
            } else {
                $rules[$key] = 'nullable';
            }
            switch ($field->type) {
                case 'datetime':
                    $rules[$key] .= '|date';
                    break;
                case 'checkbox':
                    $rules[$key] .= '|boolean';
                    break;
                case 'list':
                    $rules[$key] .= '|in:' . $field->options->pluck('value')->implode(',');
                    break;
                default:
                    $rules[$key] .= '|string';
            }
        }
        return $rules;
    }

    public function mount(): void
    {
        $this->categories = Category::with(['forms' => fn($q) => $q->where('is_active', true)->orderBy('title')])
            ->whereHas('forms', fn($q) => $q->where('is_active', true))
            ->orderBy('name')
            ->get();
    }

    public function selectForm(Form $form): void
    {
        $this->selectedForm = $form;
        $this->templateFields = $form->template->fields->load('options')->sortBy('sort_order');
        $this->fieldValues = [];
    }

    public function submit(): void
    {
        $validated = $this->validate();

        $entry = FormEntry::create([
            'form_template_id' => $this->selectedForm->form_template_id,
            'user_id'          => auth()->id(),
        ]);

        foreach ($this->templateFields as $field) {
            FieldEntryValue::create([
                'form_entry_id'    => $entry->id,
                'template_field_id'=> $field->id,
                'value'            => (string)($this->fieldValues[$field->id] ?? ''),
            ]);
        }

        session()->flash('success', 'Ваша форма успешно отправлена.');
        // Optionally reset
        $this->selectedForm = null;
        $this->templateFields = collect();
        $this->fieldValues = [];
    }

    public function render()
    {
        return view('livewire.user-fill-form');
    }
}
