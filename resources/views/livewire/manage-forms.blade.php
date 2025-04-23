<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Закрыть"></button>
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        {{-- Левая колонка: категории, формы и создание категории --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Формы</h3>
                    <button wire:click="createNewForm" class="btn btn-sm btn-primary">+ Новая форма</button>
                </div>
                <div class="card-body p-0">
                    <div class="accordion" id="formAccordion">
                        @foreach($categories as $category)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $category->id }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $category->id }}" aria-expanded="false">
                                        {{ $category->name }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $category->id }}" class="accordion-collapse collapse"
                                     data-bs-parent="#formAccordion">
                                    <div class="accordion-body list-group list-group-flush">
                                        @foreach($category->forms as $form)
                                            <button wire:click="selectForm({{ $form->id }})"
                                                    class="list-group-item list-group-item-action {{ $currentForm && $currentForm->id === $form->id ? 'active' : '' }}">
                                                {{ $form->title }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Форма добавления новой категории --}}
                    <div class="px-3 mt-3">
                        <div class="input-group">
                            <input type="text"
                                   class="form-control @error('newCategoryName') is-invalid @enderror"
                                   placeholder="Новая категория"
                                   wire:model.defer="newCategoryName">
                            <button class="btn btn-outline-secondary" wire:click="addCategory">Добавить</button>
                            @error('newCategoryName')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Правая колонка: редактор формы --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $currentForm ? 'Редактировать форму' : 'Создать новую форму' }}
                    </h3>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label class="form-label">Название</label>
                            <input type="text" class="form-control @error('formData.title') is-invalid @enderror"
                                   wire:model.defer="formData.title">
                            @error('formData.title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Описание</label>
                            <textarea class="form-control @error('formData.description') is-invalid @enderror"
                                      wire:model.defer="formData.description"></textarea>
                            @error('formData.description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Категория</label>
                            <select class="form-select @error('formData.category_id') is-invalid @enderror"
                                    wire:model.defer="formData.category_id">
                                <option value="">-- выберите --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('formData.category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Шаблон</label>
                            <select class="form-select @error('formData.form_template_id') is-invalid @enderror"
                                    wire:model.defer="formData.form_template_id">
                                <option value="">-- выберите --</option>
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                                @endforeach
                            </select>
                            @error('formData.form_template_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Баллы</label>
                            <input type="text" class="form-control @error('formData.points') is-invalid @enderror"
                                   wire:model.defer="formData.points">
                            @error('formData.points') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" wire:model.defer="formData.is_active"
                                   id="isActive">
                            <label class="form-check-label" for="isActive">Активна</label>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" wire:model.defer="formData.single_entry"
                                   id="singleEntry">
                            <label class="form-check-label" for="singleEntry">Одна запись на пользователя</label>
                        </div>

                        <button type="submit" class="btn btn-success">Сохранить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
