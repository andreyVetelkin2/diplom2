<?php

namespace App\Livewire;

use App\Interfaces\FormTemplateServiceInterface;
use App\Livewire\Forms\FormTemplateForm;
use App\Models\FormTemplate;
use Illuminate\Support\Arr;
use Livewire\Component;
use function Laravel\Prompts\alert;

//TODO Создать права на действия с шаблонами и добавить проверки, при создании шаблона надо предусмотреть множественные нажатия
class ManageTemplates extends Component
{
    public $templates;
    public $selectedTemplateId;
    public $confirmingDelete = false;
    public $templateToDeleteId = null;

    public FormTemplateForm $form;

    protected FormTemplateServiceInterface $formTemplateService;

    public function boot(FormTemplateServiceInterface $formTemplateService)
    {
        $this->formTemplateService = $formTemplateService;
    }

    public function mount()
    {
        $this->loadTemplates();
    }

    public function loadTemplates()
    {
        $this->templates = $this->formTemplateService->getAllTemplates();
    }

    public function selectTemplate($id)
    {
        $template = $this->formTemplateService->getTemplateDataById($id);

        $this->selectedTemplateId = $id;
        $this->form->fillFromTemplate($template);
    }

    public function newTemplate()
    {
        $this->reset('selectedTemplateId');
        $this->form->resetFields();
    }

    public function addField()
    {
        $this->form->fields[] = [
            'id'     => '',
            'name'     => '',
            'label'    => '',
            'type'     => 'string',
            'required' => false,
            'options'  => [],
        ];
    }

    public function removeField($index)
    {
        Arr::forget($this->form->fields, $index);
        $this->form->fields = array_values($this->form->fields);
    }

    public function deleteTemplate($id)
    {

            $template = FormTemplate::find($id);
            if ($template->forms){
                session()->flash('error', 'Шаблон не может быть удален, так как некоторые формы используют его.');
                return;
            }
            $this->formTemplateService->deleteTemplate($id);
            $this->loadTemplates();
            session()->flash('message', 'Шаблон удален.');
    }

    public function addOption($fieldIndex)
    {
        $this->form->fields[$fieldIndex]['options'][] = ['id'=>'', 'label' => '', 'value' => ''];
    }

    public function removeOption($fieldIndex, $optIndex)
    {
        Arr::forget($this->form->fields[$fieldIndex]['options'], $optIndex);
        $this->form->fields[$fieldIndex]['options'] = array_values($this->form->fields[$fieldIndex]['options']);
    }

    public function saveTemplate()
    {
        $this->form->validate();

        $this->formTemplateService->saveTemplate(
            $this->selectedTemplateId,
            $this->form->templateName,
            $this->form->fields
        );

        session()->flash('message', 'Шаблон успешно сохранен.');
        $this->loadTemplates();
    }


    public function confirmDelete($id)
    {
        $this->templateToDeleteId = $id;
        $this->confirmingDelete = true;
    }

    public function cancelDelete()
    {
        $this->confirmingDelete = false;
        $this->templateToDeleteId = null;
    }

    public function deleteConfirmed()
    {
        $this->deleteTemplate($this->templateToDeleteId);
        $this->cancelDelete();
    }


    public function render()
    {
        return view('livewire.manage-templates');
    }
}
