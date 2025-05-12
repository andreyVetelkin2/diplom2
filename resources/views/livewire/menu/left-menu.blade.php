<!--begin::Sidebar-->
<aside  class="app-sidebar bg-light" data-bs-theme="light">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="/" class="brand-link">
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">Административная панель</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
                class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="menu"
                data-accordion="false"
            >

                @foreach($arMenu as $item)
                    @if(isset($item['links']))
                        <li class="nav-item menu-open">
                            <a href="#" class="nav-link active">
                                <p>
                                    {{_($item['label'])}}
                                    <i class="nav-arrow bi bi-chevron-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @foreach($item['links'] as $link)
                                    <li class="nav-item ">
                                        <a href="{{route($link['route'])}}" class="nav-link {{ request()->is($link['active']) ? 'active' : '' }}">
                                            <i class="nav-icon bi bi-circle"></i>
                                            <p>{{ $link['label'] }}</p>
                                        </a>
                                    </li>

                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="nav-item ">
                            <a href="{{ route($item['route'])}}" class="nav-link {{ request()->is($item['active']) ? 'active' : '' }}">
                                <p>{{ $item['label'] }}</p>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->
