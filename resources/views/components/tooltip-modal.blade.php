@props(['text', 'trigger' => "hover", 'placement' => 'right', 'html' => true] )
<i class="px-1 fas fa-info-circle" data-trigger="{{$trigger}}" data-toggle-select="popover" data-placement="{{$placement}}" data-html="{{var_export($html)}}" data-content="{{ $text }}">

</i>
