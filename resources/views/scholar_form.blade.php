<!DOCTYPE html>
<html>
<head>
    <title>Поиск преподавателя в Google Scholar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Поиск научных статей преподавателя</h1>
        <form action="{{ route('scholar.fetch') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="author_name" class="form-label">Имя преподавателя:</label>
                <input type="text" class="form-control" id="author_name" name="author_name" required>
            </div>
            <button type="submit" class="btn btn-outline-primary">Найти</button>
        </form>
    </div>
</body>
</html>
