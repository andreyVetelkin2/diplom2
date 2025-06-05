
<div class="container py-4">
    <!-- Блок информации о пользователе -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-person-circle me-2 text-primary"></i>Профиль пользователя</h5>
        </div>
        <div class="card-body">
            <!-- Имя -->
            <div class="mb-3">
                <label class="form-label">Имя</label>
                <input type="text" class="form-control" wire:model="username" placeholder="Введите имя">
                <div class="form-text text-muted">Текущее: {{ $user->name }}</div>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" wire:model="useremail" placeholder="Введите email">
                <div class="form-text text-muted">Текущее: {{ $user->email }} — это ваш логин</div>
            </div>



            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="d-grid mt-4">
                <button wire:click="updateProfile" class="btn btn-outline-primary">
                    <i class="bi bi-save me-1"></i> Сохранить изменения
                </button>
            </div>
        </div>
    </div>

    <!-- Блок смены пароля -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-shield-lock me-2 text-danger"></i>Смена пароля</h5>
        </div>

        <form wire:submit.prevent="updatePassword">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Новый пароль</label>
                    <input type="password" wire:model="password" class="form-control" placeholder="Введите новый пароль">
                    @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Подтверждение пароля</label>
                    <input type="password" wire:model="password_confirmation" class="form-control" placeholder="Подтвердите пароль">
                    @error('password_confirmation') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>

            <div class="card-footer bg-white border-top-0">
                <div class="d-grid">
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-arrow-repeat me-1"></i> Обновить пароль
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
