{{--<x-app-layout>--}}

{{--        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">--}}
{{--            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">--}}
{{--                <div class="max-w-xl">--}}
{{--                    <livewire:profile.update-profile-information-form />--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">--}}
{{--                <div class="max-w-xl">--}}
{{--                    <livewire:profile.update-password-form />--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">--}}
{{--                <div class="max-w-xl">--}}
{{--                    <livewire:profile.delete-user-form />--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

<div class="row">
    <div class="col-md-3">

        <!-- Profile Image -->
        <div class="card">
            <div class="card-header">
                <h3 class="profile-username text-center">{{$username}}</h3>

                <p class="text-muted text-center">Должность</p>

            </div>
            <div class="card-body">
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>Рейтенг</b> <a class="pull-right">1,322</a>
                    </li>
                    <li class="list-group-item">
                        <b>Кол-во публикаций</b> <a class="pull-right">543</a>
                    </li>
                </ul>

                <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Публикации</h5>
            </div>
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                <tr>
                    <th>Название формы</th>
                    <th>Дата заполнения</th>
                    <th>Статус</th>
                </tr>
                </thead>
                <tbody>
                @forelse($achivments as $achivement)
                    <tr>
                        <td>
                            <a href="{{ route('form-entry', $achivement['id']) }}">
                                {{ $achivement['title'] }}
                            </a>
                        </td>
                        <td>
                            {{ $achivement['date'] }}
                        </td>
                        <td>
                            @if($achivement['status'] === 'review')
                                <span class="badge bg-warning text-dark">На проверке</span>
                            @elseif($achivement['status'] === 'approved')
                                <span class="badge bg-success">Принято</span>
                            @elseif($achivement['status'] === 'rejected')
                                <span class="badge bg-danger">Отклонено</span>
                            @else
                                <span class="badge bg-secondary">Неизвестно</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">Нет достижений</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            <div class="card-footer">
                <div class="text-center my-3 text-muted">
                    Показано {{ count($achivments) }} из {{ $totalAchivments }} достижений
                </div>

                @if(count($achivments) < $totalAchivments)
                    <div class="text-center">
                        <button wire:click="loadMore" class="btn btn-primary">
                            Загрузить ещё
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- /.col -->
</div>

{{--</x-app-layout>--}}
