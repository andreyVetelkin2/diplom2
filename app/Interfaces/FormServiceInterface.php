<?php


namespace App\Interfaces;

use Illuminate\Support\Collection;
use App\Models\Form;

interface FormServiceInterface
{
    public function getCategories(): Collection;
    public function getTemplates(): Collection;
    public function getFormById(int $id): ?Form;
    public function saveForm(array $data, ?int $formId = null): Form;
    public function addCategory(string $name): void;
}

