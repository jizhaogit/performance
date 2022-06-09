@props(['label' => '', 'placement' => 'right', 'html' => true, 'options' => [], 'selectedValue' => null, 'valueField' => 'id', 'displayField' => 'text', 'popoverstr' => '', 'tooltipField' => 'tooltip', 'name'] )
@php 
$selectedDisplay = $options[0][$displayField];
$selectedTooltip = $options[0][$tooltipField];

if ($selectedValue != null) {
    foreach($options as $option) {
        if ($option[$valueField] == $selectedValue) {
            $selectedDisplay = $option[$displayField];
            $selectedTooltip = $option[$tooltipField];
            $selectedValue = $option[$valueField];
        }
    }
} else {
    $selectedValue = $options[0][$valueField];
}
$popoverstr = str_replace("&lt;","<",$popoverstr);
$popoverstr = str_replace("&gt;",">",$popoverstr);

@endphp
<div class='tooltip-dropdown'>
    <label class="mb-0">
        {{$label}} 
        <i id="tag-info" class="fas fa-info-circle" data-trigger=" click " data-toggle-select="popover" data-placement="right" data-html="true" data-content="{{$popoverstr}}" data-original-title=""></i>
        <input type="hidden" name="{{$name}}" value="{{$selectedValue}}">
    </label>
    <div class="btn-group btn-block">
        <button type="button" class="btn btn-outline-secondary dropdown-toggle text-capitalize text-left" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-color: #ced4da;">
            {{$selectedDisplay}}
        </button>
        <div class="dropdown-menu">
            @foreach ($options as $option)        
                <a class="dropdown-item" data-value={{$option[$valueField]}} href="#" onclick="$(this).parents('.tooltip-dropdown').find('input').val($(this).data('value'));$(this).parents('.btn-group').find('button').html($(this).html());">
                    {{$option[$displayField]}}
                </a>
            @endforeach
        </div>
    </div>
</div>
