<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Закрыть"></button>
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
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
                                <h2 class="accordion-header d-flex flex-row justify-content-between"
                                    id="heading{{ $category->id }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $category->id }}" aria-expanded="false">
                                        {{ $category->name }}
                                    </button>
                                    <button wire:click="confirmDeleteCategory({{ $category->id }})"
                                            class="btn btn-sm btn-outline-danger"><i class="fas bi-trash"></i></button>
                                </h2>
                                <div id="collapse{{ $category->id }}" class="accordion-collapse collapse"
                                     data-bs-parent="#formAccordion">
                                    <div class="accordion-body list-group list-group-flush">
                                        @foreach($category->forms as $form)
                                            <div class="d-flex justify-content-between align-items-center">
                                                <button wire:click="selectForm({{ $form->id }})"
                                                        class="list-group-item list-group-item-action w-100 {{ $currentForm && $currentForm->id === $form->id ? 'active' : '' }}">
                                                    {{ $form->title }}
                                                </button>
                                                <button wire:click="confirmDeleteForm({{ $form->id }})"
                                                        class="btn btn-sm btn-danger ms-2"><i class="fas bi-trash"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>

                    <div class="px-3 mt-3 mb-3">
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

        {{-- Правая колонка --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $currentForm ? 'Редактировать форму' : 'Создать новую форму' }}
                    </h3>
                </div>
                <div class="card-body">
                    <form wire:submit="save">
                        {{-- Название --}}
                        <div class="mb-3">
                            <label class="form-label">Название</label>
                            <input type="text" class="form-control @error('formData.title') is-invalid @enderror"
                                   wire:model.defer="formData.title">
                            @error('formData.title')
                            <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Обозначение</label>
                            <input type="text" class="form-control @error('formData.slug') is-invalid @enderror"
                                   wire:model.defer="formData.slug">
                            @error('formData.slug')
                            <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Остальные поля — как в твоем шаблоне --}}
                        {{-- Обозначение, Описание, Категория, Шаблон, Баллы, Чекбоксы --}}

                        {{-- Категория --}}
                        <div class="mb-3">
                            <label class="form-label">Категория</label>
                            <select class="form-select @error('formData.category_id') is-invalid @enderror"
                                    wire:model.defer="formData.category_id">
                                <option value="">-- выберите --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('formData.category_id')
                            <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Шаблон --}}
                        <div class="mb-3">
                            <label class="form-label">Шаблон</label>
                            <select class="form-select @error('formData.form_template_id') is-invalid @enderror"
                                    wire:model.defer="formData.form_template_id">
                                <option value="">-- выберите --</option>
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                                @endforeach
                            </select>
                            @error('formData.form_template_id')
                            <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Баллы --}}
                        <div class="mb-3">
                            <label class="form-label">Баллы</label>
                            <input type="text" class="form-control @error('formData.points') is-invalid @enderror"
                                   wire:model.defer="formData.points">
                            @error('formData.points')
                            <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Чекбоксы --}}
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" wire:model.defer="formData.is_active"
                                   id="isActive">
                            <label class="form-check-label" for="isActive">Активна</label>
                        </div>

{{--                        <div class="form-check mb-3">--}}
{{--                            <input type="checkbox" class="form-check-input" wire:model.defer="formData.single_entry"--}}
{{--                                   id="singleEntry">--}}
{{--                            <label class="form-check-label" for="singleEntry">Одна запись на пользователя</label>--}}
{{--                        </div>--}}

                        <button type="submit" class="btn btn-success">Сохранить</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- Модальное окно: Удаление формы --}}
    @if ($confirmingFormDeletionId)
        <div class="modal d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5)">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Удаление формы</h5>
                    </div>
                    <div class="modal-body">
                        <p>Вы уверены, что хотите удалить эту форму? Удаление приведет к потере всех связанных
                            достижений?</p>
                    </div>
                    <div class="modal-footer">
                        <button wire:click="deleteForm" class="btn btn-danger">Удалить</button>
                        <button wire:click="cancelDelete" class="btn btn-secondary">Отмена</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Модальное окно: Удаление категории --}}
    @if ($confirmingCategoryDeletionId)
        <div class="modal d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5)">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Удаление категории</h5>
                    </div>
                    <div class="modal-body">
                        <p>Вы уверены, что хотите удалить эту категорию со всеми формами? Удаление приведет к потере
                            всех связанных достижений?</p>
                    </div>
                    <div class="modal-footer">
                        <button wire:click="deleteCategory" class="btn btn-danger">Удалить</button>
                        <button wire:click="cancelDelete" class="btn btn-secondary">Отмена</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
