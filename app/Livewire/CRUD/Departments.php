<?php

namespace App\Livewire\CRUD;

use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use App\Interfaces\Crudable;
use App\Models\Department;
use App\Models\Institute;
use App\Livewire\Forms\DepartmentForm;

class Departments extends Component implements Crudable
{
    use WithPagination;

    public DepartmentForm $form;
    public bool $editMode = false;
    public int $perPage = 10;
    public int $department_id;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->perPage = config('view.page_elem', 10);
    }

    public function store()
    {
        $validated = $this->form->validate();
        Department::create($validated);
        $this->resetFields();
        session()->flash('message', 'Кафедра создана');
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);
        $this->form->setDepartment($department);
        $this->department_id = $id;
        $this->editMode = true;
    }

    public function update()
    {
        $validated = $this->form->validate();
        $department = Department::findOrFail($this->department_id);
        $department->update($validated);
        $this->resetFields();
        session()->flash('message', 'Кафедра обновлена');
    }

    #[On('deleteConfirmed')]
    public function delete($id)
    {
        Department::findOrFail($id)->delete();
        session()->flash('message', 'Кафедра удалена');
    }

    public function resetFields()
    {
        $this->form->resetFields();
        $this->editMode = false;
        $this->department_id = 0;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.c-r-u-d.departments', [
            'departments' => Department::with('institute')->paginate($this->perPage),
            'institutes' => Institute::pluck('name', 'id'),
        ]);
    }
}
