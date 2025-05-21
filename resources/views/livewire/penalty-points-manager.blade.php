<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                Управление штрафными баллами
            </h4>

            <div class="d-flex">
                <div class="input-group" style="width: 250px;">
                    <span class="input-group-text bg-white">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text"
                           class="form-control"
                           placeholder="Поиск пользователей..."
                           wire:model.live.debounce.500ms="search">
                </div>

                <select class="form-select ms-2" style="width: 100px;" wire:model="perPage">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                    <tr>
                        <th wire:click="sortBy('name')" style="cursor: pointer;">
                            Пользователь
                            @if($sortField === 'name')
                                <i class="bi bi-chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                            @endif
                        </th>
                        <th wire:click="sortBy('penalty_points')" style="cursor: pointer;">
                            Штрафные баллы
                            @if($sortField === 'penalty_points')
                                <i class="bi bi-chevron-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                            @endif
                        </th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="fw-semibold">{{ $user->name }}</div>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>

                                    <span
                                        class="badge rounded-pill bg-{{ $user->penalty_points >= 50 ? 'danger' : ($user->penalty_points >= 20 ? 'warning' : 'secondary') }}">
                                        {{ $user->penalty_points ?? 0 }}
                                    </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"
                                        wire:click="edit({{ $user->id }})">
                                    <i class="bi bi-pencil"></i> Изменить
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">
                                Пользователи не найдены
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-top">
                <div class="text-muted small">
                    Показано с {{ $users->firstItem() }} по {{ $users->lastItem() }} из {{ $users->total() }}
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade @if($showEditModal) show d-block @endif" tabindex="-1"
         style="@if($showEditModal) display: block; @else display: none; @endif" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <!-- Заголовок -->
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-circle text-warning me-2"></i>
                        Изменить штрафные баллы
                    </h5>
                    <button type="button" class="btn-close" wire:click="resetModal"></button>
                </div>

                <!-- Тело модального окна -->
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Пользователь</label>
                        <input type="text" class="form-control" value="{{ $selectedUser?->name }}" disabled>
                    </div>

                    <div class="mb-3">

                        <label class="form-label" for="points">Добавить штрафные баллы</label>
                        <input type="number" id="points" class="form-control" wire:model.defer="points" min="0"
                               max="100">
                    </div>

                    <div class="mb-3">

                        <label class="form-label" for="comment">Обоснование</label>
                        <textarea name="comment" class="form-control" id="" cols="30" rows="10" wire:model.defer="comment"></textarea>
                    </div>

                    <div class="mb-3">

                        <label class="form-label" for="date">Дата вступления в силу</label>
                        <input type="date" id="date" class="form-control" wire:model.defer="date">
                    </div>

                    @if($selectedUser && $selectedUser->penaltyPoints->isNotEmpty())
                        <hr>
                        <h6 class="fw-bold mb-2">История штрафных баллов</h6>
                        <div class="table-responsive" style="max-height: 200px;">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                <tr>
                                    <th>Дата</th>
                                    <th>Баллы</th>
                                    <th>Обоснование</th>
                                    <th>Удалить</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($selectedUser->penaltyPoints->sortByDesc('created_at') as $penalty)
                                    <tr>
                                        <td>{{ $penalty->date }}</td>
                                        <td>{{ $penalty->penalty_points }}</td>
                                        <td>{{ $penalty->comment }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-danger"
                                                    wire:click="deletePenaltyPoint({{$penalty->id}})">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Футер -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="resetModal">Отмена</button>
                    <button type="button" class="btn btn-primary" wire:click="save">
                        <i class="bi bi-check-circle me-1"></i> Добавить Штрафной Балл
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Затемнение фона -->
    @if($showEditModal)
        <div class="modal-backdrop fade show"></div>
    @endif



    @if($showEditModal)
        <script>
            document.addEventListener('livewire:load', function () {
                const modal = new bootstrap.Modal(document.getElementById('editModal'));
                modal.show();

                document.getElementById('editModal').addEventListener('hidden.bs.modal', function () {
                @this.resetModal();
                });
            });
        </script>
    @endif
</div>
