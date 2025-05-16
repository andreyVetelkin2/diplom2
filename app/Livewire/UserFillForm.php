<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\Features\SupportValidation\BaseValidate;
use Livewire\WithFileUploads; // Add this trait
use App\Models\Category;
use App\Models\Form;
use App\Models\FormEntry;
use App\Models\FieldEntryValue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;

class UserFillForm extends Component
{
    use WithFileUploads; // Enable file uploads

    public Collection $categories;
    public ?Form $selectedForm = null;
    public Collection $templateFields;
    public array $fieldValues = [];

    #[Validate('required|date')]
    public $dateAchievement;
    #[Validate('required|numeric|between:0,1')]
    public $percent;

    public array $rows = [];
    public array $files = [];

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
                case 'file':
                    $rules[$key] .= '|file|mimes:jpg,jpeg,png,pdf|max:10240'; // Example: allow images and PDFs, max 10MB
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
        $this->rows = [];
        $this->resetFieldValues();
    }

    private function resetFieldValues(): void
    {
        $this->fieldValues = [];
        foreach ($this->templateFields as $field) {
            if ($field->type === 'checkbox') {
                $this->fieldValues[$field->id] = false;
            } else {
                $this->fieldValues[$field->id] = null;
            }
        }
    }

    private function resetForm(): void
    {
        $this->selectedForm = null;
        $this->templateFields = collect();
        $this->fieldValues = [];
        $this->rows = [];
    }

    public function addRow(): void
    {
        $this->validate();

        // Сохраняем файлы во временное хранилище компонента
        foreach ($this->templateFields as $field) {
            if ($field->type === 'file' && isset($this->fieldValues[$field->id])) {
                $this->files[] = $this->fieldValues[$field->id];
            }
        }

        $this->rows[] = $this->fieldValues;
        $this->resetFieldValues();
        $this->resetErrorBag();
    }


    public function submit(): void
    {
        if (empty($this->rows)) {
            $this->addError('rows', 'Пожалуйста, добавьте хотя бы один результат.');
            return;
        }

        if ((auth()->user()->limit_ballov_na_kvartal <= ((int)$this->selectedForm->points + auth()->user()->rating))){
            session()->flash('error', 'Превышено максимальное количество баллов доступных к получению в этом квартале.');

            return;
        }


        foreach ($this->rows as $index => $row) {
            $entry = FormEntry::create([
                'form_template_id' => $this->selectedForm->form_template_id,
                'user_id' => auth()->id(),
                'form_id' => $this->selectedForm->id,
                'status' => 'review',
                'date_achievement' => $this->dateAchievement,
                'percent' => $this->percent,
            ]);

            foreach ($this->templateFields as $field) {
                $value = $row[$field->id] ?? '';

                if ($field->type === 'file' && isset($this->files[$index])) {
                    $file = $this->files[$index];
                    $originalName = $file->getClientOriginalName();

                    // Генерируем уникальное имя файла для избежания конфликтов
                    $fileName = $this->generateUniqueFileName(
                        'uploads/' . auth()->id(),
                        $originalName
                    );

                    // Сохраняем с оригинальным именем
                    $path = $file->storeAs(
                        'uploads/' . auth()->id(),
                        $fileName,
                        'public'
                    );

                    FieldEntryValue::create([
                        'form_entry_id' => $entry->id,
                        'template_field_id' => $field->id,
                        'value' => 'storage/' . $path,
                        'original_name' => $originalName,
                        'file_type' => $file->getClientMimeType(),
                    ]);
                } else {
                    FieldEntryValue::create([
                        'form_entry_id' => $entry->id,
                        'template_field_id' => $field->id,
                        'value' => (string) $value,
                    ]);
                }
            }
        }

        session()->flash('success', 'Данные сохранены успешно.');
        $this->resetForm();
    }

    private function generateUniqueFileName(string $directory, string $originalName): string
    {
        $base = pathinfo($originalName, PATHINFO_FILENAME);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $counter = 1;

        // Проверяем существование файла
        while (Storage::disk('public')->exists("$directory/$originalName")) {
            $originalName = "{$base}($counter).{$extension}";
            $counter++;
        }

        return $originalName;
    }

    public function render()
    {
        return view('livewire.user-fill-form');
    }
}
