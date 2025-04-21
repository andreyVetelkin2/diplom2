<div class="container-fluid">
    <div class="card card-primary card-outline mb-4">
        <div class="card-header">
            <div class="card-title">Информация о роли</div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Название роли</label>
                <input type="text" class="form-control" value="{{ $role->name }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Роль</label>
                <input type="email" class="form-control" value="{{ $role->slug }}" disabled>
            </div>
        </div>
    </div>

    <div class="card card-secondary card-outline mb-4">
        <div class="card-header">
            <div class="card-title">Добавление прав в роль</div>
        </div>
        <form wire:submit.prevent="submit">
            <div class="card-body">
                <div class="mb-3">
                    <label>Выберите права:</label>
                    <select wire:model="selectedPermissions" multiple class="form-control" size="10">
                        @foreach ($allPermissions as $permission)
                            <option value="{{ $permission->id }}">
                                {{ $permission->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if (session()->has('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </form>
    </div>
</div>

