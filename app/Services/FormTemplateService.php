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
        $template = $id ? FormTemplate::findOrFail($id) : new FormTemplate();
        $template->name = $name;
        $template->save();

        $template->fields()->delete();

        foreach ($fields as $fieldData) {
            $field = $template->fields()->create([
                'name'     => $fieldData['name'],
                'label'    => $fieldData['label'],
                'type'     => $fieldData['type'],
                'required' => $fieldData['required'],
            ]);

            if ($fieldData['type'] === 'list' && !empty($fieldData['options'])) {
                $field->options()->createMany($fieldData['options']);
            }
        }
    }

    public function deleteTemplate(int $id): void
    {
        $template = FormTemplate::with('fields.options')->findOrFail($id);

        $template->delete();
    }
}
