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
                    <button wire:click="createNewForm" class="btn btn-sm btn-outline-primary">+ Новая форма</button>
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
                                                        class="btn btn-sm btn-outline-danger ms-2"><i class="fas bi-trash"></i>
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
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-semibold">{{ $currentForm ? 'Редактировать форму' : 'Создать новую форму' }}</h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        {{-- Название --}}
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control @error('formData.title') is-invalid @enderror"
                                   id="formTitle" placeholder="Название"
                                   wire:model.defer="formData.title">
                            <label for="formTitle">Название</label>
                            @error('formData.title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Описание --}}
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control @error('formData.description') is-invalid @enderror"
                                   id="formDescription" placeholder="Описание"
                                   wire:model.defer="formData.description">
                            <label for="formDescription">Описание</label>
                            @error('formData.description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Обозначение --}}
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control @error('formData.slug') is-invalid @enderror"
                                   id="formSlug" placeholder="Обозначение"
                                   wire:model.defer="formData.slug">
                            <label for="formSlug">Обозначение</label>
                            @error('formData.slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Категория --}}
                        <div class="form-floating mb-3">
                            <select class="form-select @error('formData.category_id') is-invalid @enderror"
                                    id="formCategory" wire:model.defer="formData.category_id">
                                <option value="">-- выберите --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <label for="formCategory">Категория</label>
                            @error('formData.category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Шаблон --}}
                        <div class="form-floating mb-3">
                            <select class="form-select @error('formData.form_template_id') is-invalid @enderror"
                                    id="formTemplate" wire:model.defer="formData.form_template_id">
                                <option value="">-- выберите --</option>
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                                @endforeach
                            </select>
                            <label for="formTemplate">Шаблон</label>
                            @error('formData.form_template_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Баллы --}}
                        <div class="form-floating mb-3">
                            <input type="number" step="any" class="form-control @error('formData.points') is-invalid @enderror"
                                   id="formPoints" placeholder="Баллы"
                                   wire:model.defer="formData.points">
                            <label for="formPoints">Баллы</label>
                            @error('formData.points')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Чекбокс: активна --}}
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="isActive"
                                   wire:model.defer="formData.is_active">
                            <label class="form-check-label" for="isActive">Активна</label>
                        </div>

                        {{-- Сохранить --}}
                        <button type="submit" class="btn btn-outline-success">💾 Сохранить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

        {{-- Модальное окно: Удаление формы --}}
        @if ($confirmingFormDeletionId)
            <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5)">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-danger border-2">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Удаление формы</h5>
                        </div>
                        <div class="modal-body">
                            <p>Удаление формы приведет к потере всех связанных достижений. Вы уверены?</p>
                        </div>
                        <div class="modal-footer">
                            <button wire:click="deleteForm" class="btn btn-outline-danger">Удалить</button>
                            <button wire:click="cancelDelete" class="btn btn-outline-secondary">Отмена</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Модальное окно: Удаление категории --}}
        @if ($confirmingCategoryDeletionId)
            <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5)">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-danger border-2">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Удаление категории</h5>
                        </div>
                        <div class="modal-body">
                            <p>Удаление категории приведет к удалению всех связанных форм. Вы уверены?</p>
                        </div>
                        <div class="modal-footer">
                            <button wire:click="deleteCategory" class="btn btn-outline-danger">Удалить</button>
                            <button wire:click="cancelDelete" class="btn btn-outline-secondary">Отмена</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
</div>
