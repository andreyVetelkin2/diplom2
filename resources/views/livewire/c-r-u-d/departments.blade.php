<div class="container-fluid">
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">{{ $editMode ? 'Редактировать кафедру' : 'Создать кафедру' }}</h3>
        </div>
        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif

            <form wire:submit.prevent="{{ $editMode ? 'update' : 'store' }}" class="row g-2 mb-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Название кафедры" wire:model.defer="form.name">
                    @error('form.name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6">
                    <select class="form-select" wire:model.defer="form.institute_id">
                        <option value="">Выберите институт</option>
                        @foreach ($institutes as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('form.institute_id') <span class="text-danger">{{ $message }}</span> @enderror
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
                    <th>ID</th>
                    <th>Название</th>
                    <th>Институт</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($departments as $department)
                    <tr>
                        <td>{{ $department->id }}</td>
                        <td>{{ $department->name }}</td>
                        <td>{{ $department->institute->name ?? '-' }}</td>
                        <td>
                            <button wire:click="edit({{ $department->id }})" class="btn btn-sm btn-warning">✏️</button>
                            <button wire:click="delete({{ $department->id }})" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Удалить кафедру?')">🗑</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
        <div class="card-footer clearfix">
            <div class="float-end">
                {{ $departments->links() }}
            </div>
        </div>
    </div>
</div>
