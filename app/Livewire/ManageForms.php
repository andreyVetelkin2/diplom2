<?php

namespace App\Livewire;

use App\Interfaces\FormServiceInterface;
use App\Livewire\Forms\FormData;
use Livewire\Component;
use App\Models\Form;
use App\Models\Category;
use Illuminate\Support\Collection;

class ManageForms extends Component
{
    public Collection $categories;
    public Collection $templates;
    public ?Form $currentForm = null;
    public FormData $formData;
    public string $newCategoryName = '';

    public ?int $confirmingFormDeletionId = null;
    public ?int $confirmingCategoryDeletionId = null;

    protected FormServiceInterface $formService;

    public function boot(FormServiceInterface $formService)
    {
        $this->formService = $formService;
    }

    public function mount(): void
    {
        $this->initializeData();
    }

    private function initializeData(): void
    {
        $this->categories = $this->formService->getCategories();
        $this->templates  = $this->formService->getTemplates();
    }

    public function selectForm(Form $form): void
    {
        $this->currentForm = $form;
        $this->formData->fillFromForm($form);
    }

    public function createNewForm(): void
    {
        $this->currentForm = null;
        $this->formData->resetFields();
    }

    public function save(): void
    {

        $validated = $this->formData->validate();
        $this->formService->saveForm($validated, $this->currentForm?->id);

        session()->flash('success', 'Форма успешно сохранена.');
        $this->initializeData();
    }

    public function addCategory(): void
    {
        $this->validateOnly('newCategoryName');

        $this->formService->addCategory($this->newCategoryName);
        session()->flash('success', 'Категория добавлена.');

        $this->newCategoryName = '';
        $this->initializeData();
    }

    public function confirmDeleteForm(int $formId): void
    {
        $this->confirmingFormDeletionId = $formId;
    }

    public function deleteForm(): void
    {
        if ($this->confirmingFormDeletionId) {
            Form::find($this->confirmingFormDeletionId)?->delete();
            $this->confirmingFormDeletionId = null;
            $this->currentForm = null;
            session()->flash('success', 'Форма удалена.');
            $this->initializeData();
        }
    }

    public function confirmDeleteCategory(int $categoryId): void
    {
        $this->confirmingCategoryDeletionId = $categoryId;
    }

    public function deleteCategory(): void
    {
        if ($this->confirmingCategoryDeletionId) {
            Category::find($this->confirmingCategoryDeletionId)?->delete();
            $this->confirmingCategoryDeletionId = null;
            session()->flash('success', 'Категория удалена.');
            $this->initializeData();
        }
    }

    public function cancelDelete(): void
    {
        $this->confirmingFormDeletionId = null;
        $this->confirmingCategoryDeletionId = null;
    }

    public function render()
    {
        return view('livewire.manage-forms');
    }
}
