<div class="container-fluid">
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">{{ $editMode ? 'Редактировать институт' : 'Создать институт' }}</h3>
        </div>
        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif

            <form wire:submit.prevent="{{ $editMode ? 'update' : 'store' }}" class="row g-2 mb-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Название института" wire:model.defer="form.name">
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
                @foreach ($institutes as $institute)
                    <tr>
                        <td>{{ $institute->id }}</td>
                        <td>{{ $institute->name }}</td>
                        <td>
                            <button wire:click="edit({{ $institute->id }})" class="btn btn-sm btn-warning">✏️</button>
                            <button wire:click="delete({{ $institute->id }})" class="btn btn-sm btn-outline-danger"
                                    onclick="confirmDelete({{ $institute->id }})">🗑</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
        <div class="card-footer clearfix">
            <div class="float-end">
                {{ $institutes->links() }}
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        function confirmDelete(id) {
            if (confirm(`Удалить институт?`)) {
                Livewire.dispatch('deleteConfirmed', { id });
            }
        }
    </script>
@endpush
