@extends('layouts.app')

@section('title', 'YTDLK')

@section('content')
    <div class="mx-auto pt-20 w-3/4">
        <livewire:main-container wire:key="{{now()}}"/>
    </div>
@endsection
