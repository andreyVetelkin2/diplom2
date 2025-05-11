<div>
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">Сводный отчет</h3>
        </div>

        <div class="card-body">
            <div class="container">
                {{-- Вкладки Bootstrap --}}
                <ul class="nav nav-tabs mb-3" id="reportTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'individual' ? 'active' : '' }}"
                           wire:click.prevent="switchTab('individual')"
                           data-bs-toggle="tab"
                           href="#individual-tab"
                           role="tab">Индивидуальный</a>
                    </li>
                    @can('report-on-the-departments')
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'department' ? 'active' : '' }}"
                           wire:click.prevent="switchTab('department')"
                           data-bs-toggle="tab"
                           href="#department-tab"
                           role="tab">По кафедрам</a>
                    </li>
                    @endcan
                </ul>

                {{-- Общие фильтры --}}
                <livewire:date-filter/>

                {{-- Селектор кафедры (только при нужной вкладке) --}}
                @if($activeTab === 'department')
                    <div class="form-group mt-3">
                        <label for="department">Выберите кафедру:</label>
                        <select wire:model="selectedDepartment" id="department" class="form-control">
                            <option value="">-- выберите --</option>
                            @foreach($departments as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- Таблица отчета --}}
                <div class="mt-4">
                    <table class="table table-bordered table-hover">
                        <thead>
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
                            <tr class="table-secondary font-weight-bold text-center">
                                <td colspan="5">{{ $category['category'] }}</td>
                            </tr>
                            @foreach($category['forms'] as $index => $form)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $form['name'] }}</td>
                                    <td>{{ $form['slug'] }}</td>
                                    <td>{{ $form['total'] ?? '—' }}</td>
                                    <td>
                                        @forelse($form['entries'] as $entry)
                                            {{ $entry->created_at->format('d.m.Y') }}<br>
                                        @empty
                                            —
                                        @endforelse
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Данные не найдены</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
        <div class="card-footer">
            @if($groupedData && $activeTab === 'individual')
                <div class="mt-4 text-end">
                    <button class="btn btn-success" wire:click="exportIndividual">
                        Скачать индивидуальный отчет в DOCX
                    </button>
                </div>

            @endif
                @if($groupedData && $activeTab === 'department')
                    <div class="mt-4 text-end">
                        <button class="btn btn-success" wire:click="exportDepartment">
                            Скачать отчет кафедрам в DOCX
                        </button>
                    </div>

                @endif
        </div>
    </div>
</div>
