<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <ul class="navbar-nav">
            @isset($links)
                @foreach($links as $link)
                    <li class="nav-item d-none d-md-block">
                        <a href="{{ $link['url'] }}" class="nav-link">{{ $link['name'] }}</a>
                    </li>
                @endforeach
            @endisset
        </ul>

        <ul class="navbar-nav ms-auto">
            <li class="nav-item user-menu">
                @can('manage')
                    <a href="{{ route('manager-cabinet') }}" class="nav-link">
                        Кабинет руководителя
                        @if($reviewFormsCount)
                            <span class="navbar-badge badge text-bg-warning">{{$reviewFormsCount}}</span>
                        @endif
                    </a>

                @endcan
            </li>
            <li class="nav-item dropdown user-menu">

                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <span class="d-none d-md-inline">{{ $username }}</span>

                </a>

                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <li class="user-footer d-flex justify-content-between flex-column">
                        <a href="{{ route('profile') }}" class="btn btn-default btn-flat mb-3">Профиль</a>

                        <a href="#" wire:click="logout" class="btn btn-default btn-flat mb-3">Выйти</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
