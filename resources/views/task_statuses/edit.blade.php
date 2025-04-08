@extends('layouts.app')
@section('content')
    <div class="grid col-span-full">
        <h1 class="mb-5">Изменение статуса</h1>
        <form class="w-50" method="POST" action="{{ route('task_statuses.index') }}"><input type="hidden" name="_token" value="{{ csrf_token() }}">
        @csrf
        <div class="flex flex-col">
            <div>
                <label for="name">Имя</label>
            </div>
            <div class="mt-2">
                <input type="hidden" name="_method" id="_method" value="POST">
                <input class="rounded border-gray-300 w-1/3" type="text" name="name" id="name" value="{{ $taskStatus->name }}">
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="text-rose-600">{{ $error }}</div>
                    @endforeach
                @endif
        </div>
        <div class="mt-2">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Обновить</button>
        </div>
    </div>
    </form>
@endsection
