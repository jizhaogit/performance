@props(['style' => 'primary', 'icon' => '', 'size' => 'md', 'tooltip' => '', 'tooltipPosition' => 'right', 'role' => 'button'])

<{{($attributes['href'] ?? '' ? 'a' : 'button')}} 
    role="{{$role}}"
    @if ($tooltip) 
        data-toggle-body="popover" data-placement="{{ $tooltipPosition }}" data-html="true" data-content="{{ $tooltip }}" data-trigger="hover"
    @endif
    {{ $attributes->merge(['class' => 'btn btn-'.$style. ' btn-'.$size]) }}
    >
    @if ($icon)
        <x-fa-icon :icon="$icon ?? ''" />@if($slot != '')&nbsp;@endif
    @endif
    {{ $slot }}
</{{($attributes['href'] ?? '' ? 'a' : 'button')}}>
