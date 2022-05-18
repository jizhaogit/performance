<x-side-layout>
    <x-slot name="header">
        <h3>{{ $title ?? 'HR Administration'}}</h3>
        {{-- <div class="col-md-8"> @include('hradmin.partials.tabs')</div> --}}
    </x-slot>
    @yield('tab-content')
</x-side-layout>
