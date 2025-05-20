<div class="container-fluid">
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">{{ $editMode ? 'Редактировать право' : 'Создать право' }}</h3>
        </div>
        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif

            <form wire:submit.prevent="{{ $editMode ? 'update' : 'store' }}" class="mb-4 row g-2">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Название права (пример Редактирование пользователей)" wire:model.defer="form.name">
                    @error('form.name') <span class="error">{{ $message }}</span> @enderror

                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Право (пример edit-users)" wire:model.defer="form.slug">
                    @error('form.slug') <span class="error">{{ $message }}</span> @enderror

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
                            <button wire:click="delete({{ $permission->id }})" class="btn btn-sm btn-outline-danger"
                                    onclick=" confirmDelete({{ $permission->id }})">🗑</button>
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
@push('scripts')
    <script>
        function confirmDelete(id) {
            if (confirm(`Удалить право?`)) {
                Livewire.dispatch('deleteConfirmed', { id });
            }
        }
    </script>
@endpush
