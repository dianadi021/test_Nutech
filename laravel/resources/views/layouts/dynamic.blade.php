<x-app-layout>
    <header class="w-full">@include('layouts.navigation')</header>

    @isset($header)
        {{ $header }}
    @endisset

    @isset($slot)
        {{ $slot }}
    @endisset

    @isset($footer)
        {{ $footer }}
    @endisset
</x-app-layout>
