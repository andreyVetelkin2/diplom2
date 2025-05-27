<div class="row g-4">
    <!-- Профиль -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body text-center p-4">
                <h4 class="fw-semibold mb-1">{{ $username }}</h4>
                <p class="text-muted mb-3">{{ $user->position?->name }}</p>
                <p class="text-muted mb-3">{{ $user->department?->name }}</p>
                <ul class="list-group list-group-flush text-start mb-3">
                    @if($ratingPoints )
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Рейтинг за текущий квартал</span>
                            <span class="fw-bold">
                                {{ $ratingPoints }}
{{--                                @if($user->limit_ballov_na_kvartal)--}}
{{--                                        из--}}
{{--                                        <span class="fw-bold">{{ $user->limit_ballov_na_kvartal }} </span>--}}
{{--                                @endif--}}
                            </span>
                            </li>
                        @endif

                        @if($publicationCount )
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Публикации</span>
                                <span class="fw-bold">{{ $publicationCount }}</span>
                            </li>
                        @endif

                        @if($user->citations )
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Количество цитирований</span>
                                <span class="fw-bold">{{ $user->citations }}</span>
                            </li>
                        @endif



                        @if($user->hirsh)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Индекс Хирша</span>
                                <span class="fw-bold">{{ $user->hirsh }}</span>
                            </li>
                        @endif


                </ul>


                <a href="{{ route('profile.changer', ['user' => $user->id]) }}"

                   class="btn btn-outline-primary w-100 rounded-pill mb-3">
                    ✏️ Изменить профиль
                </a>
                <button wire:click="recalculateRating" class="btn btn-outline-primary w-100 rounded-pill mb-3">
                    🔄️ Обновить рейтинг
                </button>
                @can('manage')
                    <button wire:click="recalculateForAll" class="btn btn-outline-primary w-100 rounded-pill mb-3">
                        🔄️ Обновить рейтинг всем пользователям
                    </button>
                @endcan
            </div>
        </div>
    </div>

    <!-- Публикации -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 px-4 pt-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">📚 Публикации</h5>
                <a href="{{route('reports')}}" class="btn btn-outline-primary rounded-pill"> Перейти к отчетам</a>
            </div>

            <div class="table-responsive px-4">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>Название формы</th>
                        <th>Дата</th>
                        <th>Статус</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($achivments as $achivement)
                        <tr>
                            <td>
                                <a href="{{ route('form-entry', $achivement['id']) }}"
                                   class="text-decoration-none text-primary fw-semibold">
                                    {{ $achivement['title'] }}
                                </a>
                            </td>
                            <td>{{ $achivement['date'] }}</td>
                            <td>
                                @php
                                    $statuses = [
                                        'review' => ['badge bg-warning text-dark', 'На проверке'],
                                        'approved' => ['badge bg-success', 'Принято'],
                                        'rejected' => ['badge bg-danger', 'Отклонено'],
                                    ];
                                    $default = ['badge bg-secondary', 'Неизвестно'];
                                    [$class, $text] = $statuses[$achivement['status']] ?? $default;
                                @endphp
                                <span class="{{ $class }}">{{ $text }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Нет достижений</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white border-0 text-center py-3">
                <small class="text-muted d-block mb-2">
                    Показано {{ count($achivments) }} из {{ $totalAchivments }} достижений
                </small>
                @if(count($achivments) < $totalAchivments)
                    <button wire:click="loadMore" class="btn btn-outline-primary rounded-pill px-4">
                        🔄 Загрузить ещё
                    </button>
                @endif
            </div>
        </div>
    </div>

    @can('loading-achievements')
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5>
                        Загрузить достижение
                    </h5>
                </div>
                <div class="card-body">
                    <livewire:user-fill-form/>
                </div>
                <div class="card-footer">

                </div>
            </div>
        </div>
    @endcan
</div>
