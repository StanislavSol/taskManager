@extends('layouts.app')
@section('content')
<div class="grid col-span-full">
    <h1 class="mb-5">{{ __('layout.task_statuses_edit') }}</h1>
        <form class="w-50" method="POST" action="{{ route('task_statuses.update', $taskStatus) }}">
        @csrf @method('PUT')
        <div class="flex flex-col">
            <div>
                <label for="name">{{ __('layout.table_name') }}</label>
            </div>
            <div class="mt-2">
                <input class="rounded border-gray-300 w-1/3" type="text" name="name" id="name" value="{{ $taskStatus->name }}">
            </div>
            @error('name')
                <div class="text-rose-600">{{ $message }}</div>
            @enderror
        <div class="mt-2">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">{{ __('layout.update_button') }}</button>
        </div>
    </div>
    </form>
</div>
@endsection
