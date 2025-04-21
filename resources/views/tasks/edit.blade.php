@extends('layouts.app')
@section('content')
    <div class="grid col-span-full">
        <h1 class="mb-5">Изменение задачи</h1>
        <form class="w-50" method="POST" action="{{ route('tasks.update', $task->id) }}">
        @csrf @method('PUT')
        <div class="flex flex-col">
            <div>
                <label for="name">Имя</label>
            </div>
            <div class="mt-2">
                <input class="rounded border-gray-300 w-1/3" type="text" name="name" id="name" value="{{ $task->name }}">
            </div>
            @error('name')
                <div class="text-rose-600">{{ $message }}</div>
            @enderror
            <div class="mt-2">
               <label for="description">Описание</label>
            </div>
            <div>
                <textarea class="rounded border-gray-300 w-1/3 h-32" name="description" id="description">{{ $task->description }}</textarea>
            </div>
            @error('description')
                <div class="text-rose-600">{{ $message }}</div>
            @enderror
            <div class="mt-2">
                <label for="status_id">Статус</label>
            </div>
            <div>
                <select class="rounded border-gray-300 w-1/3" name="status_id" id="status_id">
                    @foreach ($taskStatuses->all() as $status)
                        <option value="{{ $status->id }}" {{ $status->id == $task->status_id ? 'selected' : '' }}>{{ $status->name }}</option>
                    @endforeach
                </select>
            </div>
            @error('status_id')
                <div class="text-rose-600">{{ $message }}</div>
            @enderror
            </div>
            <div class="mt-2">
                <label for="assigned_by_id">Исполнитель</label>
            </div>
            <div>
                <select class="rounded border-gray-300 w-1/3" name="assigned_by_id" id="assigned_by_id">
                <option value selected="selected"></option>
                @foreach ($users->all() as $user)
                    <option value="{{ $user->id }}" {{ $user->id == $task->assigned_by_id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
                </select>
            </div>
            @error('assigned_by_id')
                <div class="text-rose-600">{{ $message }}</div>
            @enderror
        <div class="mt-2">
            <label for="labels[]">Метки</label>
        </div>
        <div>
             <select class="rounded border-gray-300 w-1/3 h-32" name="labels[]" id="labels[]" multiple>
             @foreach ($labels->all() as $label)
                 <option value="{{ $label->id }}" {{ $task->labels->find($label->id) ? 'selected' : '' }}>{{ $label->name }}</option>
             @endforeach
             </select>
        </div>
        <div class="mt-2">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Обновить</button>
        </div>
    </div>
    </form>
</div>
@endsection
