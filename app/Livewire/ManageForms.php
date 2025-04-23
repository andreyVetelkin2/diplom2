<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Category;
use App\Models\Form;
use App\Models\FormTemplate;
use Illuminate\Support\Collection;

class ManageForms extends Component
{
    public Collection $categories;
    public Collection $templates;
    public ?Form $currentForm = null;
    public array $formData;
    public string $newCategoryName = '';

    protected function rules(): array
    {
        return [
            'formData.title'             => 'required|string|max:255',
            'formData.description'       => 'nullable|string',
            'formData.category_id'       => 'required|exists:categories,id',
            'formData.points'            => 'nullable|string|max:50',
            'formData.form_template_id'  => 'required|exists:form_templates,id',
            'formData.is_active'         => 'boolean',
            'formData.single_entry'      => 'boolean',
            'newCategoryName'            => 'required_with:newCategoryName|string|max:100',
        ];
    }

    protected $validationAttributes = [
        'newCategoryName' => 'название категории',
    ];

    public function mount(): void
    {
        $this->initializeData();
    }

    private function initializeData(): void
    {
        $this->categories = Category::with('forms')->orderBy('name')->get();
        $this->templates  = FormTemplate::select('id', 'name')->orderBy('name')->get();
        $this->resetFormData();
    }

    public function selectForm(Form $form): void
    {
        $this->currentForm = $form;
        $this->formData = $form->only([
            'title', 'description', 'category_id',
            'points', 'form_template_id', 'is_active', 'single_entry'
        ]);
    }

    public function createNewForm(): void
    {
        $this->currentForm = null;
        $this->resetFormData();
    }

    private function resetFormData(): void
    {
        $this->formData = [
            'title'            => '',
            'description'      => '',
            'category_id'      => null,
            'points'           => '',
            'form_template_id' => null,
            'is_active'        => true,
            'single_entry'     => false,
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();
        $data      = $validated['formData'];

        Form::updateOrCreate([
            'id' => $this->currentForm->id ?? null
        ], $data);

        session()->flash('success', 'Форма успешно сохранена.');
        $this->initializeData();
    }

    public function addCategory(): void
    {
        $this->validateOnly('newCategoryName');

        Category::create(['name' => $this->newCategoryName]);
        session()->flash('success', 'Категория добавлена.');

        $this->newCategoryName = '';
        $this->initializeData();
    }

    public function render()
    {
        return view('livewire.manage-forms');
    }
}

