@extends('layouts.app')
@section('content')
    
    <div class="grid col-span-full">
        @include('flash::message')
        <h1 class="mb-5">Статусы</h1>
    <div>
    @auth
        <a href="{{ route('task_statuses.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
           Создать статус            
        </a>
    @endauth
    </div>
    <table class="mt-4">
        <thead class="border-b-2 border-solid border-black text-left">
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Дата создания</th>
                @auth
                <th>Действия</th>
                @endauth
            </tr>
        </thead>
        @foreach ($taskStatuses as $status)
        <tr class="border-b border-dashed text-left">
            <td>{{ $status->id }}</td>
            <td>{{ $status->name }}</td>
            <td>{{ $status->created_at->format('Y-m-d') }}</td>
            @auth
            <td>
            <a data-confirm="Вы уверены?"
               data-method="delete"
               class="text-red-600 hover:text-red-900"
               href="{{route('task_statuses.destroy', ['task_status'=>$status->id])}}"
               rel="nofollow">
            Удалить                        </a>
            <a class="text-blue-600 hover:text-blue-900" href="{{route('task_statuses.edit', ['task_status'=>$status->id])}}">
                Изменить                        </a>
            </td>
            @endauth
        </tr>
        @endforeach
            </div>
        </section>
    </div>
@endsection
