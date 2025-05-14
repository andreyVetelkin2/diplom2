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
                @can('report-on-the-departments')
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'department' ? 'active' : '' }}"
                           wire:click.prevent="switchTab('department')"
                           href="#department-tab">🏛 По кафедрам</a>
                    </li>
                @endcan

                @can('report-on-the-departments')
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'user' ? 'active' : '' }}"
                           wire:click.prevent="switchTab('user')"
                           href="#department-tab">🙍 По пользователю</a>
                    </li>
                @endcan
                @can('report-on-the-departments')
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'achievement' ? 'active' : '' }}"
                           wire:click.prevent="switchTab('achievement')"
                           href="#department-tab">📄 По типу достижений</a>
                    </li>
                @endcan
                @can('report-on-the-departments')
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
                <div class="mt-3">
                    <label for="department" class="form-label fw-medium">Выберите кафедру:</label>
                    <select wire:model="selectedDepartment" id="department" multiple class="form-select">
                        <option value="">-- выберите --</option>
                        @foreach($departments as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if($activeTab === 'user')
                <div class="mt-3">
                    <label for="user" class="form-label fw-medium">Выберите пользователя:</label>
                    <select wire:model="selectedUser" id="user" class="form-select">
                        <option value="">-- выберите --</option>
                        @foreach($users as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- Таблица --}}
            <div class="table-responsive mt-4">
                <table class="table table-hover table-striped align-middle">
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
                    @forelse($groupedData as $category)
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
                                        {{ $entry['outputLine'] }}<br>
                                    @endforeach


                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Данные не найдены</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Футер --}}
        <div class="card-footer bg-light rounded-bottom-4">
            <div class="d-flex justify-content-end gap-2 mt-2">
                @if($groupedData && $activeTab === 'individual')
                    <button class="btn btn-outline-success" wire:click="exportIndividual">
                        <i class="bi bi-download me-1"></i>Скачать индивидуальный DOCX
                    </button>
                @endif
                @if($groupedData && $activeTab === 'department')
                    <button class="btn btn-outline-success" wire:click="exportDepartment">
                        <i class="bi bi-download me-1"></i>Скачать отчёт по кафедрам
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
