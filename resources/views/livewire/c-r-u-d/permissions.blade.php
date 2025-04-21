<div class="container-fluid">
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">{{ $isEdit ? 'Редактировать право' : 'Создать право' }}</h3>
        </div>
        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif

            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}" class="mb-4 row g-2">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Название права (пример Редактирование пользователей)" wire:model.defer="name">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Право (пример edit-users)" wire:model.defer="slug">
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Обновить' : 'Создать' }}</button>
                    @if ($isEdit)
                        <button type="button" class="btn btn-secondary" wire:click="resetFields">Отмена</button>
                    @endif
                </div>
            </form>

            <table class="table table-bordered">
                <thead>
                <tr>
                    <th style="width: 10px">ID</th>
                    <th>Назание</th>
                    <th>Право</th>
                    <th style="width: 120px">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($permissions as $index => $permission)
                    <tr class="align-middle">
                        <td>{{ $permission->id  }}</td>
                        <td>{{ $permission->name }}</td>
                        <td>{{ $permission->slug }}</td>
                        <td>
                            <button wire:click="edit({{ $permission->id }})" class="btn btn-sm btn-warning">✏️</button>
                            <button wire:click="delete({{ $permission->id }})" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Удалить право?')">🗑</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
            <div class="float-end">
                {{ $permissions->links() }}
            </div>
        </div>
    </div>
</div>
