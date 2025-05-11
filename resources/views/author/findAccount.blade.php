
<x-app-layout>
<!DOCTYPE html>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authors List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .author-card {
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        .author-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .author-thumbnail {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        .authors-container {
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
</head>

    <div class="container py-4">


        <div class="authors-container">
            @foreach($authors as $author)
            <div class="author-card d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    @if(isset($author['thumbnail']))
                    <img src="{{ $author['thumbnail'] }}" alt="{{ $author['name'] }}" class="author-thumbnail me-3">
                    @endif
                    <div>
                        <h5 class="mb-1">{{ $author['name'] }}</h5>
                        <p class="mb-1 text-muted">{{ $author['affiliations'] ?? '' }}</p>
                        @if(isset($author['cited_by']['total']))
                        <small class="text-muted">Cited by: {{ number_format($author['cited_by']['total']) }}</small>
                        @endif
                    </div>
                </div>
                <button class="btn btn-outline-primary select-author"
                        data-author-id="{{ $author['author_id'] }}">
                    Выбрать
                </button>
            </div>
            @endforeach
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select-author').click(function() {
                const button = $(this);
                const authorId = button.data('author-id');

                button.prop('disabled', true).html(`
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Загрузка...
                `);

              $.ajax({
                  url: '/authors/select',
                  method: 'POST',
                  data: {
                      author_id: authorId,
                      _token: '{{ csrf_token() }}' // Добавьте эту строку
                  },

                    success: function(response) {
                        if(response.success) {
                            button.removeClass('btn-outline-primary')
                                  .addClass('btn-success')
                                  .html('Выбрано');
                        }
                    },
                    error: function() {
                        button.prop('disabled', false).html('Select');
                        alert('Error occurred. Please try again.');
                    }
                });
            });
        });
    </script>

</x-app-layout>
