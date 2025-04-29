<?php

namespace App\Livewire;

use App\Interfaces\FormServiceInterface;
use App\Livewire\Forms\FormData;
use Livewire\Component;
use App\Models\Form;
use Illuminate\Support\Collection;
//TODO Создать права на действия с формами и добавить проверки
class ManageForms extends Component
{
    public Collection $categories;
    public Collection $templates;
    public ?Form $currentForm = null;
    public FormData $formData;
    public string $newCategoryName = '';

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
        $validated = $this->validate();

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

    public function render()
    {
        return view('livewire.manage-forms');
    }
}
