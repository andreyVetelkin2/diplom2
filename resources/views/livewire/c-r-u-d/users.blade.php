<div class="container-fluid">
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">{{ $isEdit ? 'Редактировать пользователя' : 'Создать пользователя' }}</h3>
        </div>
        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif

            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}" class="mb-4 row g-2">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Имя" wire:model.defer="name">
                </div>
                <div class="col-md-4">
                    <input type="email" class="form-control" placeholder="Email" wire:model.defer="email">
                </div>
                @if (!$isEdit)
                    <div class="col-md-4">
                        <input type="password" class="form-control" placeholder="Пароль" wire:model.defer="password">
                    </div>
                @endif
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
                    <th style="width: 10px">#</th>
                    <th>Имя</th>
                    <th>Email</th>
                    <th style="width: 120px">Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $index => $user)
                    <tr class="align-middle">
                        <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td><a href="{{ route('user-detail', $user->id) }}">{{ $user->name }}</a></td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <button wire:click="edit({{ $user->id }})" class="btn btn-sm btn-warning">✏️</button>
                            <button wire:click="delete({{ $user->id }})" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Удалить пользователя?')">🗑</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
            <div class="float-end">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
