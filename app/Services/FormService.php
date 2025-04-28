<?php

namespace App\Services;

use App\Interfaces\FormServiceInterface;
use App\Models\Category;
use App\Models\Form;
use App\Models\FormTemplate;
use Illuminate\Support\Collection;

class FormService implements FormServiceInterface
{
    public function getCategories(): Collection
    {
        return Category::with('forms')->orderBy('name')->get();
    }

    public function getTemplates(): Collection
    {
        return FormTemplate::select('id', 'name')->orderBy('name')->get();
    }

    public function getFormById(int $id): ?Form
    {
        return Form::find($id);
    }

    public function saveForm(array $data, ?int $formId = null): Form
    {
        return Form::updateOrCreate(
            ['id' => $formId],
            $data
        );
    }

    public function addCategory(string $name): void
    {
        Category::create(['name' => $name]);
    }
}
