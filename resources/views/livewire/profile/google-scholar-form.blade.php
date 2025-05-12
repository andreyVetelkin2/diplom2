
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Интеграция с Google Scholar') }}</h3>
                        <br>
                        <p class="card-subtitle text-muted mt-1">
                            {{ __('Добавьте данные для интеграции с Google Scholar.') }}
                        </p>
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent="updateScholarData" class="form-horizontal" autocomplete="off">
                            <div class="form-group row mb-3">
                                <label for="author_id" class="col-sm-3 col-form-label">
                                    {{ __('ID аккаунта Google Scholar') }}
                                </label>
                                <div class="col-sm-9">
                                    <input
                                        wire:model="author_id"
                                        id="author_id"
                                        name="author_id"
                                        type="text"
                                        class="form-control"
                                        placeholder="Например: jD4G5p4AAAAJ"
                                        autocomplete="off"
                                    >
                                    <small class="form-text text-muted">
                                        {{ __('ID можно найти в URL профиля автора на Google Scholar') }}
                                    </small>
                                    @error('author_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="google_key" class="col-sm-3 col-form-label">
                                    {{ __('Токен доступа Google Scholar') }}
                                </label>
                                <div class="col-sm-9">
                                    <input
                                        wire:model="google_key"
                                        id="google_key"
                                        name="google_key"
                                        type="password"
                                        class="form-control"
                                        placeholder="Введите токен доступа"
                                        autocomplete="new-password"
                                    >
                                    <small class="form-text text-muted">
                                        {{ __('Токен можно получить в API Google Scholar') }}
                                    </small>
                                    @error('google_key')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn btn-outline-primary">
                                        {{ __('Сохранить') }}
                                    </button>

                                    @if (session()->has('saved'))
                                        <span class="text-success ml-3">
                                            <i class="bi bi-check-circle-fill"></i> {{ __('Сохранено.') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Row-->
    </div>

