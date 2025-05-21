<x-app-layout>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Загрузка данных из Google Scholar</h3>
                        </br>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('download_file'))
                            <div class="alert alert-info mb-4">
                                <a href="{{ route('download.file', ['filename' => session('download_file')]) }}"
                                   class="btn btn-primary">
                                    <i class="bi bi-download me-2"></i>Скачать данные пользователей
                                </a>
                            </div>
                        @endif

                        <form action="{{ route('users.fetch-google-scholar') }}" method="POST">
                            @csrf
                            <div class="d-flex gap-3 mb-4">
                               <button type="submit" id="submit-btn" class="btn btn-outline-primary" disabled>
                                   <i class="bi bi-cloud-download me-2"></i>Получить данные пользователей
                               </button>
                                <button type="button" class="btn btn-outline-secondary"
                                        data-bs-toggle="modal" data-bs-target="#uploadModal">
                                    <i class="bi bi-upload me-2"></i>Загрузить данные  Google Scholar
                                </button>
                                <button type="button" id="select-all" class="btn btn-outline-dark">
                                    <i class="bi bi-check-all me-2"></i>Выбрать всех
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="w-50px">Выбрать</th>
                                            <th>Имя</th>
                                            <th>Google Scholar ID</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                        <tr>
                                            <td>
                                                <input type="checkbox"
                                                       name="selected_users[]"
                                                       value="{{ $user->id }}"
                                                       class="form-check-input"
                                                       @if($user->author && $user->author->author_id) data-has-author="true" @else disabled @endif>
                                            </td>
                                            <td>{{ $user->name }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="text"
                                                           id="author-input-{{ $user->id }}"
                                                           value="{{ $user->author->author_id ?? '' }}"
                                                           class="form-control w-auto"
                                                           placeholder="Google Scholar ID"
                                                           readonly>
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-secondary edit-author-btn"
                                                            data-user-id="{{ $user->id }}">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <div class="edit-author-actions d-none gap-2" id="edit-actions-{{ $user->id }}">
                                                        <button type="button"
                                                                class="btn btn-sm btn-success save-author-btn"
                                                                data-user-id="{{ $user->id }}">
                                                            <i class="bi bi-check"></i>
                                                        </button>
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-danger cancel-edit-btn"
                                                                data-user-id="{{ $user->id }}">
                                                            <i class="bi bi-x"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('users.upload-data') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Upload Data File</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="data_file" class="form-label">Select file to upload</label>
                            <input class="form-control"
                                   type="file" id="data_file" name="data_file" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {

        // Select all button
        document.getElementById('select-all').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('input[name="selected_users[]"][data-has-author="true"]');
            const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

            checkboxes.forEach(checkbox => {
                checkbox.checked = !anyChecked;
            });

            const icon = this.querySelector('i');
            if (anyChecked) {
                   icon.classList.remove('bi-x-circle');
                            icon.classList.add('bi-check-all');

            } else {

                icon.classList.remove('bi-check-all');
                icon.classList.add('bi-x-circle');

            }
         updateSubmitButtonState();
        });


        // Edit buttons
        document.querySelectorAll('.edit-author-btn').forEach(btn => {

            btn.addEventListener('click', function() {



                const userId = this.dataset.userId;
                const input = document.getElementById(`author-input-${userId}`);

                // Show edit buttons
                document.getElementById(`edit-actions-${userId}`).classList.remove('d-none');
                // Hide edit button
                this.classList.add('d-none');
                // Make input editable
                input.readOnly = false;
                input.focus();
            });
        });

        // Cancel buttons
        document.querySelectorAll('.cancel-edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.dataset.userId;
                const input = document.getElementById(`author-input-${userId}`);

                // Reset input value
                input.value = input.defaultValue;
                input.readOnly = true;

                // Hide edit buttons
                document.getElementById(`edit-actions-${userId}`).classList.add('d-none');
                // Show edit button
                document.querySelector(`.edit-author-btn[data-user-id="${userId}"]`).classList.remove('d-none');
            });
        });

        // Save buttons
        document.querySelectorAll('.save-author-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.dataset.userId;
                const authorId = document.getElementById(`author-input-${userId}`).value;

                fetch(`/users/${userId}/update-author-id`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ author_id: authorId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Request failed');
                });
            });
        });
    });

    const downloadLinks = document.querySelectorAll('a[href*="/download/"]');

        downloadLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const originalText = link.innerHTML;

                // Показываем индикатор загрузки
                link.innerHTML = '<i class="bi bi-arrow-repeat spinner me-2"></i>Подготовка файла...';
                link.classList.add('disabled');

                fetch(this.href)
                    .then(response => {
                        if (!response.ok) throw new Error('Ошибка сети');
                        return response.blob();
                    })
                    .then(blob => {
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = 'google_scholar_data.json';
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);

                        // Обновляем страницу после скачивания
                        setTimeout(() => location.reload(), 1000);
                    })
                    .catch(error => {
                        alert('Ошибка при скачивании: ' + error.message);
                        console.error('Download error:', error);
                    })
                    .finally(() => {
                        link.innerHTML = originalText;
                        link.classList.remove('disabled');
                    });
            });
        });
    // Disable submit button when no users are selected
    const submitBtn = document.getElementById('submit-btn');
    const checkboxes = document.querySelectorAll('input[name="selected_users[]"]');

    function updateSubmitButtonState() {
                    const checkboxes = document.querySelectorAll('input[name="selected_users[]"][data-has-author="true"]');
                      const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

        const atLeastOneChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
        submitBtn.disabled = !atLeastOneChecked;
    }

    // Add event listeners to all checkboxes
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSubmitButtonState);
    });

    // Initialize button state
    updateSubmitButtonState();

    </script>

</x-app-layout>
