<div class=" ">
    <h3>Редактирование достижения</h3>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4>{{ $entry->form->title }}</h4>
        </div>
        <div class="card-body">
            <!-- Отображение статуса -->
            <div class="alert alert-info my-3">
                Текущий статус: <strong>{{ $entry->status_label }}</strong>
            </div>
            <div class="alert alert-success my-3">
                Автор достяжения: <strong>{{ $user->name }}</strong>
            </div>
            <form wire:submit.prevent="save">
                @foreach($templateFields as $field)
                    <div class="mb-3">
                        <label class="form-label">{{ $field->label }}
                            @if($field->required) <span class="text-danger">*</span> @endif
                        </label>

                        @if($field->type === 'string')
                            <input type="text"
                                   class="form-control @error('fieldValues.' . $field->id) is-invalid @enderror"
                                   wire:model.defer="fieldValues.{{ $field->id }}">

                        @elseif($field->type === 'textarea')
                            <textarea class="form-control @error('fieldValues.' . $field->id) is-invalid @enderror"
                                      wire:model.defer="fieldValues.{{ $field->id }}"></textarea>

                        @elseif($field->type === 'datetime')
                            <input type="date"
                                   class="form-control @error('fieldValues.' . $field->id) is-invalid @enderror"
                                   wire:model.defer="fieldValues.{{ $field->id }}">

                        @elseif($field->type === 'checkbox')
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input"
                                       wire:model.defer="fieldValues.{{ $field->id }}"
                                       id="field{{ $field->id }}">

                                <label class="form-check-label" for="field{{ $field->id }}"></label>
                            </div>

                        @elseif($field->type === 'list')
                            <select class="form-select @error('fieldValues.' . $field->id) is-invalid @enderror"
                                    wire:model.defer="fieldValues.{{ $field->id }}">
                                <option value="">-- выберите --</option>
                                @foreach($field->options as $opt)
                                    <option value="{{ $opt->value }}">{{ $opt->label }}</option>
                                @endforeach
                            </select>

                        @elseif($field->type === 'file')
                            @if($fieldValues[$field->id])
                                <div class="mb-2">
                                    <strong>Текущий файл:</strong>
                                    <a href="{{ asset($fieldValues[$field->id]) }}" target="_blank">Открыть</a>
                                </div>
                            @endif
                            <input type="file"
                                   wire:model="fieldValues.{{ $field->id }}"
                                   class="form-control @error('fieldValues.' . $field->id) is-invalid @enderror">
                        @endif

                        @error('fieldValues.' . $field->id)
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror


                    </div>
                @endforeach
                <div class="mb-3 d-flex flex-column">
                    <label class="form-label">Комментарий руководителя</label>
                    <span class="">{{$entry->comment}}</span>

                </div>

                        <button type="submit" class="btn btn-primary">Сохранить</button>
                        <a href="javascript:history.back()" class="btn btn-secondary">Отмена</a>
                    @can('manage')
                        <button class="btn btn-success" wire:click="confirmAction('approve')">Принять</button>
                        <button class="btn btn-danger" wire:click="confirmAction('reject')">Отклонить</button>
                    @endcan
            </form>
        </div>
    </div>





<!-- Модальное окно подтверждения -->
    @if($showConfirmModal)
        <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            @if($modalAction === 'approve') Подтвердить принятие?
                            @else Отклонить достижение?
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
                        <button type="button" class="btn btn-secondary" wire:click="$set('showConfirmModal', false)">Отмена</button>
                        <button type="button" class="btn btn-primary" wire:click="executeAction">Подтвердить</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
