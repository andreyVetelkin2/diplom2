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
            <div class="list-group">
                @foreach($categories as $cat)
                    <div class="mb-2">
                        <button class="btn btn-outline-secondary w-100 text-start"
                                data-bs-toggle="collapse"
                                data-bs-target="#cat{{ $cat->id }}">
                            {{ $cat->name }}
                        </button>
                        <div class="collapse" id="cat{{ $cat->id }}">
                            @foreach($cat->forms as $form)
                                <button wire:click="selectForm({{ $form->id }})"
                                        class="list-group-item list-group-item-action {{ $selectedForm && $selectedForm->id === $form->id ? 'active' : '' }}">
                                    {{ $form->title }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Правая колонка: отображение и заполнение выбранной формы --}}
        <div class="col-md-8">
            @if ($selectedForm)
                <div class="card">
                    <div class="card-header">
                        <h3>{{ $selectedForm->title }}</h3>
                    </div>
                    <div class="card-body">
                        <p>{{ $selectedForm->description }}</p>
                        <p><strong>Баллы:</strong> {{ $selectedForm->points }}</p>
                        <form wire:submit.prevent="submit">
                            @foreach($templateFields as $field)
                                <div class="mb-3">
                                    <label class="form-label">{{ $field->label }}
                                        @if($field->required) <span class="text-danger">*</span> @endif
                                    </label>

                                    @if($field->type === 'string')
                                        <input type="text" class="form-control @error('fieldValues.' . $field->id) is-invalid @enderror"
                                               wire:model.defer="fieldValues.{{ $field->id }}">

                                    @elseif($field->type === 'textarea')
                                        <textarea class="form-control @error('fieldValues.' . $field->id) is-invalid @enderror"
                                                  wire:model.defer="fieldValues.{{ $field->id }}"></textarea>

                                    @elseif($field->type === 'datetime')
                                        <input type="datetime-local" class="form-control @error('fieldValues.' . $field->id) is-invalid @enderror"
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
                                    @endif

                                    @error('fieldValues.' . $field->id)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach

                            <button type="submit" class="btn btn-primary">Отправить</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="alert alert-info">Выберите форму слева, чтобы заполнить её.</div>
            @endif
        </div>
    </div>
</div>
