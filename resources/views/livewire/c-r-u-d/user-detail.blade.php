<div class="container-fluid">
    {{-- Блок информации и смены пароля --}}
    <div class="card card-primary card-outline mb-4">
        <div class="card-header">
            <div class="card-title">Информация о пользователе</div>
        </div>

        <form wire:submit.prevent="updateUserInfo">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Имя</label>
                    <input type="text" class="form-control" wire:model.defer="user_field.name">
                    @error('user.name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Email адрес</label>
                    <input type="email" class="form-control" wire:model.defer="user_field.email">
                    @error('user.email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Должность</label>
                    <input type="text" class="form-control" wire:model.defer="user_field.position">
                    @error('user.position') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Кафедра</label>
                    <select class="form-select" wire:model.defer="user_field.department_id">
                        <option value="">Выберите кафедру</option>
                        @foreach ($departments as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('user.department') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                @if (session()->has('success_info'))
                    <div class="alert alert-success mt-3">
                        {{ session('success_info') }}
                    </div>
                @endif
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-outline-primary">Сохранить информацию</button>
            </div>
        </form>
    </div>


    <div class="card card-primary card-outline mb-4">
        <div class="card-header">
            <div class="card-title">Информация о пользователе</div>
        </div>

        <form wire:submit.prevent="updatePassword">
            <div class="card-body">

                <h6>Смена пароля</h6>

                <div class="mb-3">
                    <label class="form-label">Новый пароль</label>
                    <input type="password" wire:model="password" class="form-control">
                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Подтверждение пароля</label>
                    <input type="password" wire:model="password_confirmation" class="form-control">
                    @error('password_confirmation') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                @if (session()->has('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-outline-primary">Обновить пароль</button>
            </div>
        </form>
    </div>

    {{-- Блок назначения ролей и прав --}}
    <div class="card card-secondary card-outline">
        <div class="card-header">
            <div class="card-title">Роли и права</div>
        </div>

        <form wire:submit.prevent="updateRolesAndPermissions">
            <div class="card-body">
                <div class="mb-4">
                    <label class="form-label">Роли</label>
                    <select wire:model="selectedRoles" multiple class="form-control" size="5">
                        @foreach ($allRoles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedRoles') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Права</label>
                    <select wire:model="selectedPermissions" multiple class="form-control" size="10">
                        @foreach ($allPermissions as $permission)
                            <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedPermissions') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                @if (session()->has('success_roles'))
                    <div class="alert alert-success mt-3">
                        {{ session('success_roles') }}
                    </div>
                @endif
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-outline-success">Сохранить роли и права</button>
            </div>
        </form>
    </div>
</div>
