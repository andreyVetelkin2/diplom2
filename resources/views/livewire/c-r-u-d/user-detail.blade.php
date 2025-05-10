<div class="container-fluid">
    {{-- Блок информации и смены пароля --}}
    <div class="card card-primary card-outline mb-4">
        <div class="card-header">
            <div class="card-title">Информация о пользователе</div>
        </div>
        <div class="card-body">
            <!-- Имя пользователя -->
            <div class="mb-3">
                <label class="form-label">Имя</label>
                <p>Текущее: {{$user->name}}</p>
                <input type="text" class="form-control" wire:model="user.name">
            </div>

            <!-- Email (или Логин) пользователя -->
            <div class="mb-3">
                <label class="form-label">Email</label>
                <p>Текущее: {{$user->email}}</p>
                <input type="email" class="form-control" wire:model="user.email">
                <div class="form-text">Email — это ваш логин.</div>
            </div>

            <!-- Кнопка для сохранения изменений -->
            <div class="mb-3">
                <button wire:click="updateProfile" class="btn btn-primary">Сохранить изменения</button>
            </div>
        </div>

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
                <button type="submit" class="btn btn-primary">Обновить пароль</button>
            </div>
        </form>
    </div>

    {{-- Блок назначения ролей и прав --}}
{{--    <div class="card card-secondary card-outline">--}}
{{--        <div class="card-header">--}}
{{--            <div class="card-title">Роли и права</div>--}}
{{--        </div>--}}

{{--        <form wire:submit.prevent="updateRolesAndPermissions">--}}
{{--            <div class="card-body">--}}
{{--                <div class="mb-4">--}}
{{--                    <label class="form-label">Роли</label>--}}
{{--                    <select wire:model="selectedRoles" multiple class="form-control" size="5">--}}
{{--                        @foreach ($allRoles as $role)--}}
{{--                            <option value="{{ $role->id }}">{{ $role->name }}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                    @error('selectedRoles') <small class="text-danger">{{ $message }}</small> @enderror--}}
{{--                </div>--}}

{{--                <div class="mb-4">--}}
{{--                    <label class="form-label">Права</label>--}}
{{--                    <select wire:model="selectedPermissions" multiple class="form-control" size="10">--}}
{{--                        @foreach ($allPermissions as $permission)--}}
{{--                            <option value="{{ $permission->id }}">{{ $permission->name }}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                    @error('selectedPermissions') <small class="text-danger">{{ $message }}</small> @enderror--}}
{{--                </div>--}}

{{--                @if (session()->has('success_roles'))--}}
{{--                    <div class="alert alert-success mt-3">--}}
{{--                        {{ session('success_roles') }}--}}
{{--                    </div>--}}
{{--                @endif--}}
{{--            </div>--}}

{{--            <div class="card-footer">--}}
{{--                <button type="submit" class="btn btn-success">Сохранить роли и права</button>--}}
{{--            </div>--}}
{{--        </form>--}}
{{--    </div>--}}
</div>
