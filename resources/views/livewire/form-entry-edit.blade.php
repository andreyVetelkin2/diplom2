<div class="container py-4">
    <h3 class="mb-4">🎯 Редактирование достижения</h3>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h4 class="mb-0">{{ $entry->form->title }}</h4>
        </div>

        <div class="card-body">
            <div class="mb-3">
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <div>Текущий статус: <strong>{{ $entry->status_label }}</strong></div>
                </div>
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <i class="bi bi-person-circle me-2"></i>
                    <div>Автор достижения: <strong>{{ $user->name }}</strong></div>
                </div>
            </div>

            <form wire:submit.prevent="save">
                <div class="mb-4">
                    <label class="form-label">📊 Возможное количество баллов за достижение</label>
                    <input type="text" readonly disabled
                           class="form-control " value="{{ ($entry->form->points) }}" >
                </div>

                <div class="mb-4">
                    <label class="form-label">📅 Дата достижения</label>
                    <input type="date"
                           class="form-control @error('date_achievement') is-invalid @enderror"
                           wire:model.defer="date_achievement">
                    @error('date_achievement')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">✍ Процент участия</label>
                    <input type="text"
                           class="form-control @error('percent') is-invalid @enderror"
                           wire:model.defer="percent">
                    @error('percent')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                @foreach($templateFields as $field)
                    <div class="mb-4">
                        <label class="form-label">
                            {{ $field->label }}
                            @if($field->required)
                                <span class="text-danger">*</span>
                            @endif
                        </label>

                        @switch($field->type)
                            @case('string')
                            <input type="text"
                                   class="form-control @error('fieldValues.' . $field->id) is-invalid @enderror"
                                   wire:model.defer="fieldValues.{{ $field->id }}">
                            @break

                            @case('textarea')
                            <textarea class="form-control @error('fieldValues.' . $field->id) is-invalid @enderror"
                                      wire:model.defer="fieldValues.{{ $field->id }}"></textarea>
                            @break

                            @case('datetime')
                            <input type="date"
                                   class="form-control @error('fieldValues.' . $field->id) is-invalid @enderror"
                                   wire:model.defer="fieldValues.{{ $field->id }}">
                            @break

                            @case('checkbox')
                            <div class="form-check form-switch ">
                                <input type="checkbox" class="form-check-input"
                                       wire:model.defer="fieldValues.{{ $field->id }}"
                                       id="field{{ $field->id }}">
                                <label class="form-check-label" for="field{{ $field->id }}">Да</label>
                            </div>
                            @break

                            @case('list')
                            <select class="form-select @error('fieldValues.' . $field->id) is-invalid @enderror"
                                    wire:model.defer="fieldValues.{{ $field->id }}">
                                <option value="">-- выберите --</option>
                                @foreach($field->options as $opt)
                                    <option value="{{ $opt->value }}">{{ $opt->label }}</option>
                                @endforeach
                            </select>
                            @break

                            @case('file')
                            @if($fieldValues[$field->id])
                                <div class="mb-2">
                                    <strong>📎 Текущий файл:</strong>
                                    <a href="{{ asset($fieldValues[$field->id]) }}" target="_blank" class="ms-2 text-decoration-underline">Открыть</a>
                                </div>
                            @endif
                            <input type="file"
                                   wire:model="fieldValues.{{ $field->id }}"
                                   class="form-control @error('fieldValues.' . $field->id) is-invalid @enderror">
                            @break
                        @endswitch

                        @error('fieldValues.' . $field->id)
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach

                <div class="mb-4">
                    <label class="form-label">📝 Комментарий руководителя</label>
                    <div class="p-2 border rounded bg-light">{{ $entry->comment ?: '—' }}</div>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-outline-primary">
                        Сохранить
                    </button>
                    <a href="javascript:history.back()" class="btn btn-outline-secondary">
                        Отмена
                    </a>
                    @can('manage')
                        <button type="button" class="btn btn-outline-success" wire:click="confirmAction('approve')">
                            Принять
                        </button>
                        <button type="button" class="btn btn-outline-danger" wire:click="confirmAction('reject')">
                            Отклонить
                        </button>
                    @endcan
                </div>
            </form>
        </div>
    </div>

    {{-- Модальное окно --}}
    @if($showConfirmModal)
        <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-sm">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            @if($modalAction === 'approve')
                                Подтвердить принятие?
                            @else
                                Отклонить достижение?
                            @endif
                        </h5>
                        <button type="button" class="btn-close" wire:click="$set('showConfirmModal', false)"></button>
                    </div>

                    <div class="modal-body">
                        @if($modalAction === 'reject')
                            <label class="form-label">Причина отклонения</label>
                            <textarea wire:model.defer="rejectionComment" class="form-control"></textarea>
                        @else
                            <p>Вы уверены, что хотите принять это достижение?</p>
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" wire:click="$set('showConfirmModal', false)">
                            Отмена
                        </button>
                        <button type="button" class="btn btn-outline-primary" wire:click="executeAction">
                            Подтвердить
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
