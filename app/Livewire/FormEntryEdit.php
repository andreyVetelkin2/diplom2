<?php

namespace App\Livewire;

use App\Models\FormEntry;
use App\Models\FieldEntryValue;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FormEntryEdit extends Component
{
    use WithFileUploads;

    public FormEntry $entry;
    public $templateFields;
    public $fieldValues = [];
    public $files = [];
    public $comment;

    public $date_achievement;

    public User $user;
    public $showConfirmModal = false;
    public $modalAction = null; // 'approve' | 'reject'
    public $rejectionComment = '';

    public function confirmAction(string $action)
    {
        $this->modalAction = $action;
        $this->showConfirmModal = true;
    }

    public function executeAction()
    {
        if ($this->modalAction === 'approve') {
            $this->entry->status = 'approved';
            $this->entry->comment = $this->comment;
        } elseif ($this->modalAction === 'reject') {
            $this->entry->status = 'rejected';
            $this->entry->comment = $this->rejectionComment;
        }

        $this->entry->save();
        $this->showConfirmModal = false;
        session()->flash('success', 'Статус достижения обновлен.');
    }


    public function updated($property)
    {
        $this->validateOnly($property, $this->rules());
    }

    protected function rules(): array
    {
        $rules = [];
        foreach ($this->templateFields as $field) {
            $key = "fieldValues.{$field->id}";
            $rules[$key] = $field->required ? 'required' : 'nullable';

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
                case 'file':
                    //$rules[$key] .= '|nullable|file|mimes:jpg,jpeg,png,pdf|max:10240';
                    break;
                default:
                    $rules[$key] .= '|string';
            }
        }
        return $rules;
    }

    public function save()
    {
        $this->validate();

        foreach ($this->templateFields as $field) {
            $existing = FieldEntryValue::firstOrNew([
                'form_entry_id' => $this->entry->id,
                'template_field_id' => $field->id,
            ]);

            $rawValue = $this->fieldValues[$field->id] ?? null;

            if ($field->type === 'checkbox') {
                $existing->value = $rawValue ? '1' : '0';

            } elseif ($field->type === 'file') {
                if ($rawValue instanceof UploadedFile) {
                    // Удалить старый файл
                    if ($existing->value && Storage::disk('public')->exists(str_replace('storage/', '', $existing->value))) {
                        Storage::disk('public')->delete(str_replace('storage/', '', $existing->value));
                    }

                    $fileName = $rawValue->getClientOriginalName();
                    $path = $rawValue->storeAs('uploads/' . auth()->id(), $fileName, 'public');

                    $existing->value = 'storage/' . $path;
                    $existing->original_name = $fileName;
                    //$existing->file_type = $rawValue->getClientMimeType();
                }

            } else {
                $existing->value = (string)$rawValue;
            }

            $existing->save();
        }

        $this->entry->comment = $this->comment;
        $this->entry->status = 'review';
        $this->entry->date_achievement = $this->date_achievement;
        $this->entry->save();
        session()->flash('success', 'Данные успешно обновлены.');
    }


    public function mount(FormEntry $entry)
    {
        $this->user = User::find($entry->user_id);

        $this->entry = $entry->load([
            'form.template.fields.options',
            'fieldEntryValues'
        ]);
         $this->date_achievement = $this->entry->date_achievement;
        $this->templateFields = $this->entry->form->template->fields->sortBy('sort_order');

        // Заполнение текущих значений
        foreach ($this->templateFields as $field) {
            $value = $this->entry->fieldEntryValues
                ->firstWhere('template_field_id', $field->id);

            $raw = $value?->value ?? null;

            if ($field->type === 'checkbox') {
                $this->fieldValues[$field->id] = filter_var($raw, FILTER_VALIDATE_BOOLEAN);
            } else {
                $this->fieldValues[$field->id] = $raw;
            }
        }
        $this->comment =  $this->entry->comment;



        //dd($this->templateFields);
    }

    public function render()
    {
        return view('livewire.form-entry-edit');
    }
}
