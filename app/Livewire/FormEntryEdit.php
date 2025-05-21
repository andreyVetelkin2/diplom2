<?php

namespace App\Livewire;

use App\Models\FormEntry;
use App\Models\FieldEntryValue;
use App\Models\User;
use App\Services\RatingUpdateService;
use Illuminate\Support\Carbon;
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
    public $percent;

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
            if (auth()->user()->limit_ballov_na_kvartal) {
                // Проверяем, что дата достижения относится к текущему кварталу
                $achievementDate = Carbon::parse($this->entry->date_achievement);
                $isCurrentQuarter = $achievementDate->between(
                    now()->startOfQuarter(),
                    now()->endOfQuarter()
                );

                // Если дата в текущем квартале, проверяем лимит баллов
                if ($isCurrentQuarter &&
                    auth()->user()->limit_ballov_na_kvartal <= ((int)$this->entry->form->points + auth()->user()->rating)
                ) {
                    session()->flash('error', 'Превышено максимальное количество баллов, доступных к получению в этом квартале. Для изменения статуса показателя необходимо изменить дату достижения.');
                    return;
                }
            }
            $this->entry->status = 'approved';
            $this->entry->comment = $this->comment;
        } elseif ($this->modalAction === 'reject') {
            $this->entry->status = 'rejected';
            $this->entry->comment = $this->rejectionComment;

            foreach ($this->templateFields as $field) {
                $existing = FieldEntryValue::firstOrNew([
                    'form_entry_id' => $this->entry->id,
                    'template_field_id' => $field->id,
                ]);
                $fieldData = $this->fieldValues[$field->id];
                $existing->comment = $fieldData['comment'] ?? null;
                $existing->save();
            }
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
            $key = "fieldValues.{$field->id}.value";
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
                    $rules["fieldValues.{$field->id}.file"] = 'nullable|max:10240';
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

            $fieldData = $this->fieldValues[$field->id] ?? [];

            if ($field->type === 'file') {
                // Обработка файла
                $file = $fieldData['file'] ?? null;

                if ($file instanceof UploadedFile) {
                    // Удалить старый файл
                    if ($existing->value && Storage::disk('public')->exists(str_replace('storage/', '', $existing->value))) {
                        Storage::disk('public')->delete(str_replace('storage/', '', $existing->value));
                    }

                    $fileName = $file->getClientOriginalName();
                    $path = $file->storeAs('uploads/' . auth()->id(), $fileName, 'public');

                    $existing->value = 'storage/' . $path;
                    $existing->original_name = $fileName;
                }
            } else {
                // Обработка обычных полей
                $rawValue = $fieldData['value'] ?? null;

                if ($field->type === 'checkbox') {
                    $existing->value = $rawValue ? '1' : '0';
                } else {
                    $existing->value = (string)$rawValue;
                }
            }

            // Сохраняем комментарий для всех типов полей
            $existing->comment = $fieldData['comment'] ?? null;
            $existing->save();
        }

        $this->entry->comment = $this->comment;
        $this->entry->status = 'review';
        $this->entry->date_achievement = $this->date_achievement;
        $this->entry->percent = $this->percent;
        $this->entry->save();

        $this->ratingService = app(RatingUpdateService::class);
        $this->ratingService->recalculateForUser($this->user->id);

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
        $this->percent = $this->entry->percent;
        $this->templateFields = $this->entry->form->template->fields->sortBy('sort_order');
        $this->comment = $this->entry->comment;

        foreach ($this->templateFields as $field) {
            $value = $this->entry->fieldEntryValues
                ->firstWhere('template_field_id', $field->id);

            // Для всех полей используем единую структуру
            $this->fieldValues[$field->id] = [
                'value' => $field->type === 'checkbox'
                    ? filter_var($value?->value, FILTER_VALIDATE_BOOLEAN)
                    : $value?->value,
                'comment' => $value?->comment,
                'file' => $field->type === 'file' ? $value?->value : null
            ];
        }
    }

    public function render()
    {
        return view('livewire.form-entry-edit');
    }
}
