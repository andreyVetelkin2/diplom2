<div class="container-fluid">
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">{{ $editMode ? 'Редактировать должность' : 'Создать должность' }}</h3>
        </div>
        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif

            <form wire:submit.prevent="{{ $editMode ? 'update' : 'store' }}" class="row g-2 mb-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Название должности" wire:model.defer="form.name">
                    @error('form.name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-outline-primary">{{ $editMode ? 'Обновить' : 'Создать' }}</button>
                    @if ($editMode)
                        <button type="button" class="btn btn-outline-secondary" wire:click="resetFields">Отмена</button>
                    @endif
                </div>
            </form>

            <table class="table table-bordered">
                <thead>
                <tr>
                    <th style="width: 50px">ID</th>
                    <th>Название</th>
                    <th style="width: 120px">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($positions as $position)
                    <tr>
                        <td>{{ $position->id }}</td>
                        <td>{{ $position->name }}</td>
                        <td>
                            <button wire:click="edit({{ $position->id }})" class="btn btn-sm btn-warning">✏️</button>
                            <button wire:click="delete({{ $position->id }})" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Удалить институт?')">🗑</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
        <div class="card-footer clearfix">
            <div class="float-end">
                {{ $positions->links() }}
            </div>
        </div>
    </div>
</div>
