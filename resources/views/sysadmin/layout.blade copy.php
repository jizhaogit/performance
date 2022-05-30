<x-side-layout>
    <x-slot name="header">
        <h3>{{ $title ?? 'System Administration'}}</h3>
        {{-- <div class="col-md-8"> @include('sysadmin.partials.tabs')</div> --}}
    </x-slot>
    @yield('tab-content')
</x-side-layout>



<div class="row">
    <div class="col-12 col-sm-6">
        <h3>System Administrator</h3>
    </div>
</div>
