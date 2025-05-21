<?php

namespace App\Livewire\CRUD;

use App\Livewire\Forms\PositionForm;
use App\Models\Position;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Positions extends Component
{

    use WithPagination;

    public PositionForm $form;
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
        Position::create($validated);
        $this->resetFields();
        session()->flash('message', 'Должность создана');
    }

    public function edit($id)
    {
        $institute = Position::findOrFail($id);
        $this->form->setPosition($institute);
        $this->form->name = $institute->name;
        $this->institute_id = $id;
        $this->editMode = true;
    }

    public function update()
    {
        $validated = $this->form->validate();
        $institute = Position::findOrFail($this->institute_id);
        $institute->update($validated);
        $this->resetFields();
        session()->flash('message', 'Должность обновлена');
    }

    #[On('deleteConfirmed')]
    public function delete($id)
    {
        Position::findOrFail($id)->delete();
        session()->flash('message', 'Должность удалена');
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
        return view('livewire.c-r-u-d.positions', [
            'positions' => Position::paginate($this->perPage),
        ]);
    }

}
