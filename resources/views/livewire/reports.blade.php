<div>
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">
            <h3 class="mb-0">📊 Сводный отчёт</h3>
        </div>

        <div class="card-body">
            {{-- Вкладки --}}
            <ul class="nav nav-pills mb-4 gap-2" id="reportTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'individual' ? 'active' : '' }}"
                       wire:click.prevent="switchTab('individual')"
                       href="#individual-tab">👤 Индивидуальный</a>
                </li>
                @canany(['report-on-the-departments', 'report-on-the-department'])
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'department' ? 'active' : '' }}"
                           wire:click.prevent="switchTab('department')"
                           href="#department-tab">🏛 По кафедрам</a>
                    </li>
                @endcanany

                @can('report-on-the-user')
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'user' ? 'active' : '' }}"
                           wire:click.prevent="switchTab('user')"
                           href="#department-tab">🙍 По пользователю</a>
                    </li>
                @endcan
                @can('report-on-the-forms')
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'forms' ? 'active' : '' }}"
                           wire:click.prevent="switchTab('forms')"
                           href="#department-tab">📄 По типу достижений</a>
                    </li>
                @endcan
                @can('report-on-the-position')
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'position' ? 'active' : '' }}"
                           wire:click.prevent="switchTab('position')"
                           href="#department-tab">💼 По должности</a>
                    </li>
                @endcan
            </ul>

            {{-- Фильтры --}}
            <livewire:date-filter/>

            @if($activeTab === 'department')
                @can('report-on-the-departments')
                    <div class="mt-3">
                        <label for="department" class="form-label fw-medium">Выберите кафедру:</label>
                        <select wire:model="selectedDepartment" id="department" multiple class="form-select">
                            @foreach($departments as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endcan
                @can('report-on-the-department')
                    @cannot('report-on-the-departments')
                        <div class="mt-3">
                            <label for="department" class="form-label fw-medium">Выберите кафедру:</label>
                            <select wire:model="selectedDepartment" id="department" multiple class="form-select">
                                <option
                                    value="{{ auth()->user()->department->id }}">{{ auth()->user()->department->name }}</option>
                            </select>
                        </div>
                    @endcannot
                @endcan


            @endif

            @if($activeTab === 'user')
                <div class="mt-3">
                    <label for="user" class="form-label fw-medium">Выберите пользователя:</label>
                    <select wire:model="selectedUser" id="user" class="form-select" multiple>
                        @foreach($users as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if($activeTab === 'position')
                <div class="mt-3">
                    <label for="user" class="form-label fw-medium">Выберите должность:</label>
                    <select wire:model="selectedPositions" id="position" class="form-select" multiple>
                        @foreach($positions as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            @if($activeTab === 'forms')
                <div class="mt-3">
                    <label for="user" class="form-label fw-medium">Выберите тип достижения:</label>
                    <select wire:model="selectedForms" id="form" class="form-select" multiple>
                        @foreach($forms as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="table-responsive mt-4">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>№</th>
                        <th>Показатель</th>
                        <th>Обозначение</th>
                        <th>Баллы</th>
                        <th>Выходные данные</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($groupedData as $block)
                        {{-- Блок пользователя --}}
                        <tr class="table-primary text-center fw-bold">
                            <td colspan="5">{{ $block['user'] }}</td>
                        </tr>

                        @forelse($block['sections'] as $category)
                            {{-- Категория --}}
                            <tr class="table-secondary text-center fw-bold">
                                <td colspan="5">{{ $category['category'] }}</td>
                            </tr>
                            @foreach($category['forms'] as $index => $form)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $form['name'] }}</td>
                                    <td><span class="text-muted">{{ $form['slug'] }}</span></td>
                                    <td><span class="badge bg-info text-dark">{{ $form['total'] ?? '—' }}</span></td>
                                    <td class="small text-muted">
                                        @foreach($form['entries'] as $entry)
                                            {!! nl2br(e($entry['outputLine'])) !!}
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Категорий нет</td>
                            </tr>
                        @endforelse
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Нет ни одного отчёта для отображения.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>


        </div>

        {{-- Футер --}}
        <div class="card-footer bg-light rounded-bottom-4">
            <div class="d-flex justify-content-end gap-2 mt-2">
                @if($groupedData )
                    <button class="btn btn-outline-success" wire:click="export">
                        <i class="bi bi-download me-1"></i>Скачать отчет в DOCX
                    </button>
                @endif

            </div>
        </div>
    </div>
</div>
