<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">

            <div class="col-sm-12">
                {{-- Проверяем, есть ли крошки для отображения --}}
                @if (!empty($breadcrumbs))
                    <ol class="breadcrumb float-sm-start">
                        {{-- Перебираем массив крошек --}}
                        @foreach ($breadcrumbs as $crumb)
                            <li class="breadcrumb-item {{ $crumb['is_last'] ? 'active' : '' }}"
                                @if ($crumb['is_last']) aria-current="page" @endif>

                                {{-- Если не последняя крошка - выводим ссылку --}}
                                @if (!$crumb['is_last'])
                                    <a href="{{ $crumb['url'] }}">{{ $crumb['text'] }}</a>
                                    {{-- Если последняя крошка - выводим просто текст --}}
                                @else
                                    {{ $crumb['text'] }}
                                @endif
                            </li>
                        @endforeach
                    </ol>
                @endif
                {{-- Если крошек нет (например, только главная), можно отобразить пустой <ol> или ничего --}}
                {{-- @else
                    <ol class="breadcrumb float-sm-end"></ol>
                --}}
            </div>
        </div>
    </div>
</div>
