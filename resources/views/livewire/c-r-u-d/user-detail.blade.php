<div class="container-fluid">
    <div class="card card-primary card-outline mb-4">
        <div class="card-header">
            <div class="card-title">Информация о пользователе</div>
        </div>

        <form wire:submit.prevent="updatePassword">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Имя</label>
                    <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email адрес</label>
                    <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                    <div class="form-text">Мы никогда не делимся email'ом</div>
                </div>

                <hr>

                <h6>Смена пароля</h6>

                <div class="mb-3">
                    <label class="form-label">Новый пароль</label>
                    <input type="password" wire:model="password" class="form-control">
                    @error('newPassword') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Подтверждение пароля</label>
                    <input type="password" wire:model="password_confirmation" class="form-control">
                    @error('newPasswordConfirmation') <small class="text-danger">{{ $message }}</small> @enderror
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
</div>
