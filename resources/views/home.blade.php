@extends('layouts.app')

@section('title', 'YTDLK')

@section('content')
    <div class="h-full w-3/4">
        <livewire:main-container wire:key="{{now()}}"/>
    </div>
@endsection
