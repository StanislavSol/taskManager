@extends('layouts.app')
@section('content')
<div class="grid max-w-screen-xl px-4 pt-20 pb-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12 lg:pt-28">
    <div class="grid col-span-full">
        <h2 class="mb-5">Просмотр задачи: Доработать команду подготовки БД        <a href="{{ route('tasks.edit', [$task->id])}}">&#9881;</a>
        </h1>
        <p><span class="font-black">Имя:</span> {{ taskName }}</p>
        <p><span class="font-black">Статус:</span> {{ $taskDescription }}</p>
        <p><span class="font-black">Описание:</span> {{ $taskStatus }}</p>
        <p><span class="font-black">Метки:</span></p>
    </div>
</div>

@endsection
