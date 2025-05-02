<?php


namespace App\Interfaces;


interface FormTemplateServiceInterface
{
    public function getAllTemplates(): mixed;
    public function getTemplateById(int $id): mixed;
    public function saveTemplate(?int $id, string $name, array $fields): void;
    public function getTemplateDataById(int $id): array;
    public function deleteTemplate(int $id): void;

}
