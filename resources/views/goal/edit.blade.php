<x-side-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit: {{ $goal-> title}}
        </h2>
        <small><a href="{{ route('goal.index') }}">Back to list</a></small>
    </x-slot>

    <div class="container-fluid">
        <form action="{{ route ('goal.update', $goal->id)}}" method="POST" onsubmit="confirm('Are you sure you want to update Goal ?')">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-12">
                    <x-tooltip-dropdown name="goal_type_id" :options="$goaltypes" label="Goal Type" tooltipField="description" displayField="name" :selectedValue="$goal->goal_type_id" />
                    <x-input label="Goal Title" name="title" :value="$goal->title"/>
                </div>                                
                <div class="col-12">
                    <x-xdropdown :list="$tags" label="Tags" name="tag_ids[]" :selected="array_column($goal->tags->toArray(), 'id')" class="tags" multiple/>
                </div>
                <div class="col-12">
                    <!-- <x-textarea id="what" label="What" name="what" :value="$goal->what" /> -->
                    <label for='what'>Description</label>
                    <textarea id="what" name="what" :value="$goal->what">{!!$goal->what!!}</textarea>
                    <!-- <x-textarea id="measure_of_success" class="content' label="Measures of Success" name="measure_of_success" :value="$goal->measure_of_success" /> -->
                    <label for='measure_of_success'>Measure of Success</label>
                    <textarea id="measure_of_success" name="measure_of_success" :value="$goal->measure_of_success">{!!$goal->measure_of_success!!}</textarea>
                </div>
                <div class="col-sm-6">
                    <x-input label="Start Date" type="date" name="start_date" :value="$goal->start_date ? $goal->start_date->format('Y-m-d') : ''" />
                </div>
                <div class="col-sm-6">
                    <x-input label="End Date" type="date" name="target_date" :value="$goal->target_date ? $goal->target_date->format('Y-m-d') : ''" />
                </div>
                <div class="col-12 text-center mb-3">
                    <x-button type="submit" class="btn-lg"> Save </x-button>
                </div>
            </div>
        </form>
    </div>
    @push('css')
        <link rel="stylesheet" href="{{ asset('css/bootstrap-multiselect.min.css') }}">
    @endpush

    @push('js')
    <script src="{{ asset('js/bootstrap-multiselect.min.js')}} "></script>

    <script>
        $(document).ready(() => {
            $('.tags').multiselect({
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true
            });
        });
    </script>
    <script src="//cdn.ckeditor.com/4.17.2/basic/ckeditor.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            CKEDITOR.replace('what', {
                toolbar: [ ["Bold", "Italic", "Underline", "-", "NumberedList", "BulletedList", "-", "Outdent", "Indent"] ],disableNativeSpellChecker: false  });
            CKEDITOR.replace('measure_of_success', {
                toolbar: [ ["Bold", "Italic", "Underline", "-", "NumberedList", "BulletedList", "-", "Outdent", "Indent"] ],disableNativeSpellChecker: false  });
        });
    </script>
    <script>
        if(!!window.performance && window.performance.navigation.type === 2)
        {
            console.log('Reloading');
            window.location.reload();
        }
        window.isDirty = true;
        $('form').on('submit', () => {
            window.isDirty = false;
        });
        let originalData = $('form').serialize();
        $(document).ready(function () {
            originalData = $('form').serialize();
        });
        window.onbeforeunload = function () {
            if (!window.isDirty) {
                return;
            }
            for (var i in CKEDITOR.instances) {
                CKEDITOR.instances[i].updateElement();
            };
            const currentData = $('form').serialize();
            if (currentData != originalData) {
                return "If you continue you will lose any unsaved information";
            }
        };
    </script>
    @endpush
</x-side-layout>
