<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Поиск автора Google Scholar</h4>
                    </div>
                    <div class="card-body">
                        <!-- Форма поиска по ID -->
                        <form action="{{ route('authors.store') }}" method="POST" class="mb-4">
                            @csrf
                            <div class="mb-3">
                                <label for="author_id" class="form-label">Введите ID аккаунта Google Scholar:</label>
                                <input type="text"
                                       class="form-control @error('author_id') is-invalid @enderror"
                                       id="author_id"
                                       name="author_id"
                                       placeholder="Например: jD4G5p4AAAAJ"
                                       required>
                                @error('author_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">ID можно найти в URL профиля автора на Google Scholar</small>
                            </div>
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-search me-2"></i> Найти по ID
                            </button>
                        </form>

           <form id="searchByNameForm" method="POST" action="{{ route('authors.index') }}">
               @csrf
               <div class="mb-3">
                   <label for="mauthors" class="form-label">Поиск по фамилии и имени:</label>
                   <input type="text"
                          class="form-control"
                          id="mauthors"
                          name="mauthors"
                          placeholder="Например: Иванов Иван"
                          required>
               </div>
               <button type="submit" class="btn btn-outline-primary">
                   <i class="fas fa-user me-2"></i> Найти по имени
               </button>
           </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function submitSearchForm() {
            const searchValue = document.getElementById('mauthors').value;
            if (searchValue) {
                window.location.href = "{{ route('authors.index', '') }}/" + encodeURIComponent(searchValue);
            }
        }
    </script>
</x-app-layout>
