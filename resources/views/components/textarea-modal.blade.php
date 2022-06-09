@props(['disabled' => false, 'showError' => true, 'label' => '', 'value' => '', 'info'=> '','tooltip'=>''])

<label>
    {{ __($label) }}
     @if($tooltip != '')
    <i class="fas fa-info-circle" data-trigger="hover" data-toggle-select="popover" data-placement="right" data-html="true" data-content="{{ $tooltip }}"></i>
    @endif
    <textarea {!! $attributes->merge(['class' => 'form-control']) !!}>{{$value}}</textarea>
    @if ($info != '')
    <small class="text-muted">
        {{ __($info) }}
    </small>
    @endif
    @if ($showError !== 'false') 
        <small class="text-danger">{{ $errors->first($attributes['name']) }}</small>
    @endif
</label>