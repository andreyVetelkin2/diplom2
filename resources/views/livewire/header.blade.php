<nav class="app-header navbar navbar-expand bg-body sticky-top shadow-sm" style="z-index: 1020;">
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

        <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item me-3 position-relative">
                @can('manage')
                    <a href="{{ route('manager-cabinet') }}" class="nav-link d-flex align-items-center position-relative">
                        <i class="bi bi-gear-fill me-1"></i>
                        Кабинет руководителя
                        @if($reviewFormsCount)
                            <span
                                class="navbar-badge badge text-bg-warning">
                {{$reviewFormsCount}}
              </span>
                        @endif
                    </a>
                @endcan
            </li>

            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle fs-5 me-1"></i>
                    <span class="d-none d-md-inline">{{ $username }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <li class="px-3 py-2">
                        <a href="{{ route('profile') }}" class="btn btn-outline-primary w-100 mb-2">Профиль</a>
                        <button wire:click="logout" class="btn btn-outline-danger w-100">Выйти</button>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
