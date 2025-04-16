@extends('layouts.app')
@section('content')
    
    <div class="grid col-span-full">
        @include('flash::message')
        <h1 class="mb-5">Задачи</h1>
    <div>
    @auth
        <a href="{{ route('tasks.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
           Создать задачу            
        </a>
    @endauth
    </div>
    <table class="mt-4">
        <thead class="border-b-2 border-solid border-black text-left">
            <tr>
                <th>ID</th>
                <th>Статус</th>
                <th>Имя</th>
                <th>Автор</th>
                <th>Исполнитель</th>
                <th>Дата создания</th>
                @auth
                <th>Действия</th>
                @endauth
            </tr>
        </thead>
        @foreach ($tasks as $task)
        <tr class="border-b border-dashed text-left">
            <td>{{ $task->id }}</td>
            <td>{{ $taskStatuses::find($task->status_id)->name }}</td>
            <td><a class="text-blue-600 hover:text-blue-900" href="{{ route('tasks.show', [ $task->id]) }}">{{ $task->name }}</a></td>
            <td>{{ $users::find($task->creator_by_id)->name }}</td>
            <td>{{ $users::find($task->assigned_by_id)->name ?? '' }}</td>
            <td>{{ $task->created_at->format('Y-m-d') }}</td>
            @auth
            <td>
            @if (Auth::user()->id === $task->creator_by_id) 
                <a data-confirm="Вы уверены?"
                   data-method="delete"
                   class="text-red-600 hover:text-red-900"
                   href="{{ route('tasks.destroy', [$task->id]) }}"
                   rel="nofollow">
                   Удалить                        </a>
            @endif
            <a class="text-blue-600 hover:text-blue-900" href="{{ route('tasks.edit', [$task->id])}}">
                Изменить                        </a>
            </td>
            @endauth
        </tr>
        @endforeach
            </div>
        </section>
    </div>
@endsection
