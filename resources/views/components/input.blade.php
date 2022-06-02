@props(['disabled' => false, 'showError' => true, 'label' => '', 'info' => '','tooltip'=>'', 'size' => 'md'])
<label>
    {{ __($label) }}
    @if($tooltip != '')
    <i class="fas fa-info-circle" data-trigger="hover" data-toggle-select="popover" data-placement="right" data-html="true" data-content="{{ $tooltip }}"></i>
    @endif
    @if ($info != '')
    <small class="text-muted">
        {{ __($info) }}
    </small>
    @endif
    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'form-control form-control-'.$size]) !!} autocomplete="off">
    <small class="text-danger error-{{$attributes['name'] ?? ''}}">
        @if ($showError !== 'false') 
            {{ $errors->first($attributes['name']) }}
        @endif
    </small>
</label>
