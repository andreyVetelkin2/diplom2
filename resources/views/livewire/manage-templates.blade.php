<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Шаблоны</h5>
            </div>
            <ul class="list-group list-group-flush">
                @foreach($templates as $tpl)
                    <li class="list-group-item d-flex justify-content-between align-items-center {{ $selectedTemplateId === $tpl->id ? 'active' : '' }}"
                        wire:key="template-{{ $tpl->id }}">
                        <div wire:click="selectTemplate({{ $tpl->id }})" style="cursor: pointer; flex-grow: 1;">
                            {{ $tpl->name }}
                        </div>
                        <button class="btn btn-outline-danger btn-sm"
                                wire:click.stop="confirmDelete({{ $tpl->id }})"
                                title="Удалить шаблон">
                            <i class="fas bi-trash"></i>
                        </button>
                    </li>
                @endforeach
            </ul>
            <div class="card-footer">
                <button class="btn btn-sm btn-outline-success float-right" wire:click="newTemplate">Создать новый шаблон
                </button>
            </div>
        </div>
    </div>
    <div class="modal fade @if($confirmingDelete) show d-block @endif" tabindex="-1" role="dialog"
         style="@if($confirmingDelete) background-color: rgba(0, 0, 0, 0.5); @else display: none; @endif">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Подтверждение удаления</h5>
                    <button type="button" class="btn-close" wire:click="cancelDelete"></button>
                </div>
                <div class="modal-body">
                    <p>Вы уверены, что хотите удалить этот шаблон? Удаление приведет к удалению всех заполненых
                        достижений, использующих этот шаблон.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" wire:click="cancelDelete">Отмена</button>
                    <button type="button" class="btn btn-outline-danger" wire:click="deleteConfirmed">Удалить</button>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Редактор шаблона</h5>
            </div>

            <div class="card-body">
                @if(session()->has('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
                @endif
                @if(session()->has('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <div class="alert alert-warning">
                    Внимание! Шаблон должен содержать одно поле с кодом title, name, label или nazvanie для правильного
                    формирования отчетности
                </div>
                <div class="form-group mb-3">
                    <label>Название шаблона</label>
                    <input type="text" class="form-control @error('templateName') is-invalid @enderror"
                           wire:model.defer="form.templateName">
                    @error('templateName') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                @foreach($form->fields as $index => $field)
                    <div class="card mb-3" wire:key="field-{{ $field['id'] }}">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">Код поля</label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                               class="form-control @error('form.fields.'.$index.'.name') is-invalid @enderror"
                                               placeholder="Код" wire:model.defer="form.fields.{{ $index }}.name">
                                        @error('form.fields.'.$index.'.name') <span
                                            class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">Имя поля</label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                               class="form-control @error('form.fields.'.$index.'.label') is-invalid @enderror"
                                               placeholder="Имя поля" wire:model.defer="form.fields.{{ $index }}.label">
                                        @error('form.fields.'.$index.'.label') <span
                                            class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">Тип поля</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" wire:model.defer="form.fields.{{ $index }}.type">
                                            <option value="string">Строка</option>
                                            <option value="datetime">Дата/Время</option>
                                            <option value="checkbox">Чекбокс</option>
                                            <option value="list">Список</option>
                                            <option value="file">Файл</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label">Обязательное</label>
                                    <div class="col-sm-10">
                                        <input type="checkbox" class="form-check-input" id="required-{{ $index }}"
                                               wire:model.defer="form.fields.{{ $index }}.required">
                                    </div>
                                </div>
                            </div>

                            @if($field['type'] === 'list')
                                <div class="mt-3" wire:key="options-{{ $index }}">
                                    <label class="mb-3">Элементы списка</label>
                                    @foreach($field['options'] as $optIndex => $opt)
                                        <div class="form-row row mb-2" wire:key="option{{ $opt['id'] }}">
                                            <div class="col-5">
                                                <input type="text" class="form-control" placeholder="Название поля"
                                                       wire:model.defer="form.fields.{{ $index }}.options.{{ $optIndex }}.label">
                                            </div>
                                            <div class="col-5">
                                                <input type="text" class="form-control" placeholder="Значение"
                                                       wire:model.defer="form.fields.{{ $index }}.options.{{ $optIndex }}.value">
                                            </div>
                                            <div class="col-2">
                                                <button class="btn btn-outline-danger btn-sm"
                                                        wire:click.prevent="removeOption({{ $index }},(selector) {{ $optIndex }})">
                                                    Удалить
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                    <button class="btn btn-outline-primary btn-sm"
                                            wire:click.prevent="addOption({{ $index }})">Добавить элементы списка
                                    </button>
                                </div>
                            @endif

                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-outline-danger btn-sm"
                                    wire:click="removeField({{ $index }})">Удалить поле
                            </button>
                        </div>
                    </div>
                @endforeach

                <button class="btn btn-outline-secondary" wire:click="addField">Добавить поле</button>
                <button class="btn btn-outline-primary float-right" wire:click="saveTemplate">Сохранить шаблон</button>
            </div>
        </div>
    </div>
</div>

