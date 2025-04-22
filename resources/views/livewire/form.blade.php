<div>
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Заполнение формы</h3>
        </div>
        <div class="card-body">
            @if (session()->has('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif
            <div class="mb-3">
                <div class="form-group">
                    <label>Выберите шаблон</label>
                    <select class="form-control" wire:model.live="selectedTemplate">
                        <option value="">-- Выберите шаблон --</option>
                        @foreach($templates as $template)
                            <option value="{{ $template->id }}">{{ $template->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            @if($fields)
                <form wire:submit.prevent="submit">
                    @foreach($fields as $field)
                        <div class="mb-3">
                            <div class="form-group">
                                <label for="field-{{ $field['id'] }}">
                                    {{ $field['label'] }}
                                    @if($field['required']) <span class="text-danger">*</span> @endif
                                </label>

                                @switch($field['type'])
                                    @case('string')
                                    <input type="text" id="field-{{ $field['id'] }}" class="form-control" wire:model.defer="formData.{{ $field['name'] }}">
                                    @break

                                    @case('datetime')
                                    <input type="datetime-local" id="field-{{ $field['id'] }}" class="form-control" wire:model.defer="formData.{{ $field['name'] }}">
                                    @break

                                    @case('checkbox')
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" id="field-{{ $field['id'] }}" class="custom-control-input" wire:model.defer="formData.{{ $field['name'] }}">
                                        <label class="custom-control-label" for="field-{{ $field['id'] }}"></label>
                                    </div>
                                    @break

                                    @case('list')
                                    <select id="field-{{ $field['id'] }}" class="form-control" wire:model.defer="formData.{{ $field['name'] }}">
                                        <option value="">-- Выберите --</option>
                                        @foreach($field['options'] as $opt)
                                            <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                                        @endforeach
                                    </select>
                                    @break

                                    @default
                                    <input type="text" id="field-{{ $field['id'] }}" class="form-control" wire:model.defer="formData.{{ $field['name'] }}">
                                @endswitch

                                @error('formData.' . $field['name'])
                                <span class="text-sm text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @endforeach
                    <button type="submit" class="btn btn-primary">Отправить</button>
                </form>
            @endif
        </div>
    </div>
</div>
