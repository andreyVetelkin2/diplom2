<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        {{-- Левая колонка: категории и активные формы --}}
        <div class="col-md-4">
            <div class="accordion" id="categoryAccordion">
                @foreach($categories as $cat)
                    <div class="accordion-item mb-2 shadow-sm border-0 rounded">
                        <h2 class="accordion-header" id="heading{{ $cat->id }}">
                            <button class="accordion-button collapsed bg-light fw-semibold text-dark"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#cat{{ $cat->id }}"
                                    aria-expanded="false"
                                    aria-controls="cat{{ $cat->id }}">
                                <i class="bi bi-folder me-2 text-primary"></i> {{ $cat->name }}
                            </button>
                        </h2>
                        <div id="cat{{ $cat->id }}" class="accordion-collapse collapse"
                             data-bs-parent="#categoryAccordion">
                            <div class="accordion-body py-2 px-3">
                                @foreach($cat->forms as $form)
                                    <button wire:click="selectForm({{ $form->id }})"
                                            class="btn w-100 text-start mb-1
                                        {{ $selectedForm && $selectedForm->id === $form->id
                                            ? 'btn-outline-primary text-white'
                                            : 'btn-outline-primary' }}">
                                        <i class="bi bi-file-earmark-text me-1"></i> {{ $form->title }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


        {{-- Правая колонка: отображение и заполнение выбранной формы --}}
        <div class="col-md-8">
            @if ($selectedForm)
                <div class="card shadow-sm border-0 rounded-lg">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">{{ $selectedForm->title }}</h3>
                    </div>
                    <div class="card-body">
                        <p class="fs-5">{{ $selectedForm->description }}</p>
                        <p><strong>Баллы:</strong> {{ $selectedForm->points }}</p>

                        <form>
                            <div class="mb-3">
                                <label class="form-label">Дата достижения
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control @error('dateAchievement') is-invalid @enderror"
                                       wire:model.defer="dateAchievement">
                            </div>

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
                                        <textarea
                                            class="form-control @error('fieldValues.' . $field->id) is-invalid @enderror"
                                            wire:model.defer="fieldValues.{{ $field->id }}"></textarea>
                                    @elseif($field->type === 'datetime')
                                        <input type="datetime-local"
                                               class="form-control @error('fieldValues.' . $field->id) is-invalid @enderror"
                                               wire:model.defer="fieldValues.{{ $field->id }}">
                                    @elseif($field->type === 'checkbox')
                                        <div class="form-check form-switch ">
                                            <input type="checkbox" class="form-check-input"
                                                   wire:model.defer="fieldValues.{{ $field->id }}"
                                                   id="field{{ $field->id }}">
                                            <label class="form-check-label" for="field{{ $field->id }}"></label>
                                        </div>
                                    @elseif($field->type === 'list')
                                        <select
                                            class="form-select @error('fieldValues.' . $field->id) is-invalid @enderror"
                                            wire:model.defer="fieldValues.{{ $field->id }}">
                                            <option value="">-- выберите --</option>
                                            @foreach($field->options as $opt)
                                                <option value="{{ $opt->value }}">{{ $opt->label }}</option>
                                            @endforeach
                                        </select>
                                    @elseif($field->type === 'file')
                                        <input type="file"
                                               wire:model.defer="fieldValues.{{ $field->id }}"
                                               class="form-control @error('fieldValues.' . $field->id) is-invalid @enderror">
                                    @endif

                                    @error('fieldValues.' . $field->id)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach

                            <div class="d-flex justify-content-between">
                                <button type="button" wire:click="addRow" class="btn btn-outline-secondary">
                                    <i class="bi bi-plus-circle me-2"></i> Добавить результат
                                </button>
                                <button type="button" wire:click="submit" class="btn btn-outline-primary"
                                        @if(empty($rows)) disabled @endif>
                                    <i class="bi bi-save me-2"></i> Сохранить все
                                </button>
                            </div>
                        </form>

                        @if(!empty($rows))
                            <div class="mt-3">
                                <h5>Добавленные результаты ({{ count($rows) }})</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-light">
                                        <tr>
                                            @foreach($templateFields as $field)
                                                <th>{{ $field->label }}</th>
                                            @endforeach
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($rows as $rowIndex => $row)
                                            <tr>
                                                @foreach($templateFields as $field)
                                                    <td>
                                                        @if($field->type === 'file' && isset($files[$rowIndex]))
                                                            {{ $files[$rowIndex]->getClientOriginalName() }}
                                                        @else
                                                            {{ $row[$field->id] ?? '' }}
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="alert alert-info">Выберите форму слева, чтобы заполнить её.</div>
            @endif
        </div>

    </div>
</div>
