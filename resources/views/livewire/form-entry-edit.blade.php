<div class="container mt-4">
    <h3>Редактирование достижения</h3>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h4>{{ $entry->form->title }}</h4>
        </div>
        <div class="card-body">
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

                <button type="submit" class="btn btn-primary">Сохранить</button>
                <a href="{{ route('profile') }}" class="btn btn-secondary">Отмена</a>
            </form>
        </div>
    </div>
</div>
