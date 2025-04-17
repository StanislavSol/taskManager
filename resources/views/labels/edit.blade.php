@extends('layouts.app')
@section('content')
<div class="grid col-span-full">
    <h1 class="mb-5">Изменение метки</h1>
        <form class="w-50" method="POST" action="{{ route('labels.update', $labels) }}">
        @csrf @method('PUT')
        <div class="flex flex-col">
            <div>
                <label for="name">Имя</label>
            </div>
            <div class="mt-2">
                <input class="rounded border-gray-300 w-1/3" type="text" name="name" id="name" value="{{ $label->name }}">
            </div>
            @error('name')
                <div class="text-rose-600">{{ $message }}</div>
            @enderror
            <div>
                <textarea class="rounded border-gray-300 w-1/3 h-32" name="description" id="description">{{ $label->description }}</textarea>
            </div>
            @error('description')
                <div class="text-rose-600">{{ $message }}</div>
            @enderror
        <div class="mt-2">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">Обновить</button>
        </div>
    </div>
    </form>
</div>
@endsection
