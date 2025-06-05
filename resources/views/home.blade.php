<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @session('theme') data-theme="{{$value}}" @endsession>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>YTDLK</title>
	@livewireStyles
	@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<livewire:main-container wire:key="{{now()}}"/>
@livewireScripts
</body>
</html>
