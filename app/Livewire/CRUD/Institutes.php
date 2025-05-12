<?php
namespace App\Livewire\CRUD;

use App\Models\Institute;
use Livewire\Component;
use Livewire\WithPagination;
use App\Interfaces\Crudable;
use App\Livewire\Forms\InstituteForm;

class Institutes extends Component implements Crudable
{
    use WithPagination;

    public InstituteForm $form;
    public bool $editMode = false;
    public int $perPage = 10;
    public int $institute_id;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->perPage = config('view.page_elem', 10);
    }

    public function store()
    {
        $validated = $this->form->validate();
        Institute::create($validated);
        $this->resetFields();
        session()->flash('message', 'Институт создан');
    }

    public function edit($id)
    {
        $institute = Institute::findOrFail($id);
        $this->form->setInstitute($institute);
        $this->form->name = $institute->name;
        $this->institute_id = $id;
        $this->editMode = true;
    }

    public function update()
    {
        $validated = $this->form->validate();
        $institute = Institute::findOrFail($this->institute_id);
        $institute->update($validated);
        $this->resetFields();
        session()->flash('message', 'Институт обновлён');
    }

    public function delete($id)
    {
        Institute::findOrFail($id)->delete();
        session()->flash('message', 'Институт удалён');
    }

    public function resetFields()
    {
        $this->form->resetFields();
        $this->editMode = false;
        $this->institute_id = 0;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.c-r-u-d.institutes', [
            'institutes' => Institute::paginate($this->perPage),
        ]);
    }
}
