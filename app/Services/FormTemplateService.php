<?php


namespace App\Services;


use App\Interfaces\FormTemplateServiceInterface;
use App\Models\FormTemplate;

class FormTemplateService implements FormTemplateServiceInterface
{

    public function getTemplateDataById(int $id): array
    {
        $template = $this->getTemplateById($id);

        return [
            'id' => $template->id,
            'name' => $template->name,
            'fields' => $template->fields->map(function ($field) {
                return [
                    'id'       => $field->id,
                    'name'     => $field->name,
                    'label'    => $field->label,
                    'type'     => $field->type,
                    'required' => (bool)$field->required,
                    'options'  => $field->type === 'list'
                        ? $field->options->map(fn($opt) => [
                            'id' => $opt->id,
                            'label' => $opt->label,
                            'value' => $opt->value,
                        ])->toArray()
                        : [],
                ];
            })->toArray(),
        ];
    }

    public function getAllTemplates(): mixed
    {
        return FormTemplate::with('fields.options')->get();
    }

    public function getTemplateById(int $id): mixed
    {
        return FormTemplate::with('fields.options')->findOrFail($id);
    }

    public function saveTemplate(?int $id, string $name, array $fields): void
    {
        $template = $id ? FormTemplate::with('fields.options')->findOrFail($id) : new FormTemplate();
        $template->name = $name;
        $template->save();

        $existingFieldIds = $template->fields->pluck('id')->toArray();
        $incomingFieldIds = [];

        foreach ($fields as $fieldData) {
            if (isset($fieldData['id']) && $fieldData['id']) {
                // Обновление существующего поля
                $field = $template->fields()->find($fieldData['id']);
                if ($field) {
                    $field->update([
                        'name'     => $fieldData['name'],
                        'label'    => $fieldData['label'],
                        'type'     => $fieldData['type'],
                        'required' => $fieldData['required'],
                    ]);
                    $incomingFieldIds[] = $field->id;
                }
            } else {
                // Новое поле
                $field = $template->fields()->create($fieldData);
                $incomingFieldIds[] = $field->id;
            }

            // Работа с опциями (только если тип "list")
            if ($fieldData['type'] === 'list') {
                $existingOptions = $field->options()->pluck('id')->toArray();
                $incomingOptions = [];

                foreach ($fieldData['options'] as $optionData) {
                    if (isset($optionData['id'])&& $optionData['id']) {
                        $option = $field->options()->find($optionData['id']);
                        if ($option) {
                            $option->update([
                                'label' => $optionData['label'],
                                'value' => $optionData['value'],
                            ]);
                            $incomingOptions[] = $option->id;
                        }
                    } else {
                        $newOption = $field->options()->create([
                            'label' => $optionData['label'],
                            'value' => $optionData['value'],
                        ]);
                        $incomingOptions[] = $newOption->id;
                    }
                }

//                // Удаление устаревших опций
//                $toDeleteOptions = array_diff($existingOptions, $incomingOptions);
//                $field->options()->whereIn('id', $toDeleteOptions)->delete();
            }
        }

        // Удаление удалённых полей
        $toDeleteFields = array_diff($existingFieldIds, $incomingFieldIds);
        $template->fields()->whereIn('id', $toDeleteFields)->delete();
    }


    public function deleteTemplate(int $id): void
    {
        $template = FormTemplate::with('fields.options')->findOrFail($id);

        $template->delete();
    }
}
