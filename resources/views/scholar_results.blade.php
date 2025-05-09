<div class="author-profile">
    @if($thumbnail)
        <img src="{{ $thumbnail }}" alt="{{ $author }}" class="author-photo">
    @endif

    <h2>{{ $author }}</h2>


    @if(!empty($interests))
        <h3>Научные интересы:</h3>
        <ul>
            @foreach($interests as $interest)
                <li>{{ $interest }}</li>
            @endforeach
        </ul>
    @endif
</div>
