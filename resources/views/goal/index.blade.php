<x-side-layout>
    <x-slot name="header">
        <h3>My Goals</h3>
        @include('goal.partials.tabs')
    </x-slot>
    @if($type != 'supervisor' && !$disableEdit)
    @if(request()->is('goal/current'))
    <x-button icon="plus-circle" data-toggle="modal" data-target="#addGoalModal">
        Create New Goal
    </x-button>
    <x-button icon="clone" href="{{ route('goal.library') }}">
        Add Goal from Goal Bank
    </x-button>
    <x-button icon="question" href="{{ route('resource.goal-setting') }} " target="_blank" tooltip='Click here to access goal setting resources and examples (opens in new window).'>
        Need Help?
    </x-button>
    @endif

    @endif
    <div class="mt-4">
        {{-- {{$dataTable->table()}} --}}

        <div class="row">
         @if ($type == 'current' || $type == 'supervisor')
            @if($type == 'supervisor')
                <div class="col-12 mb-4">
                    @if($goals->count() != 0)
                        These goals have been shared with you by your supervisor and reflect current priorities. Consider these goals when creating your own.
                    @else
                        <div class="alert alert-warning alert-dismissible no-border"  style="border-color:#d5e6f6; background-color:#d5e6f6" role="alert">
                        <span class="h5" aria-hidden="true"><i class="icon fa fa-info-circle"></i><b>Your supervisor is not currently sharing any goals with you.</b></span>
                        </div>
                    @endif
                </div>
            @endif
            @foreach ($goals as $goal)

                <div class="col-12 col-sm-3">
                    @include('goal.partials.card')
                </div>

            @endforeach
            @else
             <div class="col-12 col-sm-12">
                @include('goal.partials.target-table',['goals'=>$goals])
            </div>
            @endif
        </div>
        {{ $goals->links() }}
    </div>

@include('goal.partials.supervisor-goal')
@include('goal.partials.goal-detail-modal')
<div class="modal fade" id="addGoalModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="addGoalModalLabel">Create New Goal</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-4">
        <form id="goal_form" action="{{ route ('goal.store')}}" method="POST">
            @csrf
            <div class="row">
                <div class="col-6">

                    <label>
                        Goal Type
                        <select class="form-control" name="goal_type_id">
                            @foreach ($goaltypes as $item)
                                <option value="{{ $item->id }}" data-desc="{{ $item->description }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <small class="goal_type_text">@if($goaltypes) {{ $goaltypes[0]->description }} @endif</small>
                    </div>
                       <div class="col-6">
                    <x-input label="Goal Title" name="title" tooltip='A short title (1-3 words) used to reference the goal throughout the Performance platform.' />
                    <small class="text-danger error-title"></small>
                    </div>
                       <div class="col-6">
                    <x-textarea id="what" label="What" name="what" tooltip='A concise opening statement of what you plan to achieve. For example, "My goal is to deliver informative MyPerformance sessions to ministry audiences".'  />
                    <small class="text-danger error-what"></small>
                  </div>
                       <div class="col-6">
                    <x-textarea id="why" label="Why" name="why" tooltip='Why this goal is important to you and the organization (value of achievement). For example, "This will improve the consistency and quality of the employee experience across the BCPS".'  />
                    <small class="text-danger error-why"></small>
                   </div>
                       <div class="col-6">
                    <x-textarea id="how" label="How" name="how" tooltip='A few high level steps to achieve your goal. For example, "I will do this by working closely with ministry colleagues to develop presentations that respond to the need of their employees in advance of each phase of the performance management cycle".' />
                    <small class="text-danger error-how"></small>
                  </div>
                       <div class="col-6">
                    <x-textarea id="measure_of_success" label="Measures of Success" name="measure_of_success" tooltip='A qualitative or quantitative measure of success for your goal. For example, "Deliver a minimum of 2 sessions per month that reach at least 100 people"'  />
                    <small class="text-danger error-measure_of_success"></small>
                </div>
                <div class="col-sm-6">
                    <x-input label="Start Date " class="error-start" type="date" name="start_date"  />
                    <small  class="text-danger error-start_date"></small>
                </div>
                <div class="col-sm-6">
                    <x-input label="End Date " class="error-target" type="date" name="target_date"  />
                     <small  class="text-danger error-target_date"></small>
                </div>
                <div class="col-12">
                    <div class="card mt-3 p-3" icon="fa-question">
                        <span>Supporting Material</span>
                        <a href="{{route('resource.goal-setting')}}" target="_blank">Goal Setting Resources</a>
                    </div>
                </div>
                <div class="col-12 text-left pb-5 mt-3">
                    <x-button type="button" class="btn-md btn-submit"> Save Changes</x-button>
                </div>
            </div>
        </form>
      </div>

    </div>
  </div>
</div>


    <x-slot name="js">
        {{-- {{$dataTable->scripts()}} --}}
    <script src="//cdn.ckeditor.com/4.17.2/basic/ckeditor.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            CKEDITOR.replace('what', {
                toolbar: "Custom",
                toolbar_Custom: [
                    ["Bold", "Italic", "Underline"],
                    ["NumberedList", "BulletedList"],
                    ["Outdent", "Indent"],
                ],
            });
            CKEDITOR.replace('why', {
                toolbar: "Custom",
                toolbar_Custom: [
                    ["Bold", "Italic", "Underline"],
                    ["NumberedList", "BulletedList"],
                    ["Outdent", "Indent"],
                ],
            });
            CKEDITOR.replace('how', {
                toolbar: "Custom",
                toolbar_Custom: [
                    ["Bold", "Italic", "Underline"],
                    ["NumberedList", "BulletedList"],
                    ["Outdent", "Indent"],
                ],
            });
            CKEDITOR.replace('measure_of_success', {
                toolbar: "Custom",
                toolbar_Custom: [
                    ["Bold", "Italic", "Underline"],
                    ["NumberedList", "BulletedList"],
                    ["Outdent", "Indent"],
                ],
            });
        });
    </script>

    <script>
    $('body').popover({
        selector: '[data-toggle]',
        trigger: 'hover',
    });

    $('select[name="goal_type_id"]').trigger('change');

    $('select[name="goal_type_id"]').on('change',function(e){
        console.log(this);
        var desc = $('option:selected', this).attr('data-desc');;
        console.log(desc);
        $('.goal_type_text').text(desc);
    });

    $(document).on('click', '.btn-submit', function(e){
        e.preventDefault();
        for (var i in CKEDITOR.instances){
            CKEDITOR.instances[i].updateElement();
        };
        $.ajax({
            url:'/goal',
            type : 'POST',
            data: $('#goal_form').serialize(),
            success: function (result) {
                console.log(result);
                if(result.success){
                    window.location.href= '/goal';
                }
            },
            error: function (error){
                var errors = error.responseJSON.errors;
                $('.text-danger').each(function(i, obj) {
                    $('.text-danger').text('');
                });
                Object.entries(errors).forEach(function callback(value, index) {
                    var className = '.error-' + value[0];
                    $(className).text(value[1]);
                });
            }
        });

    });
    $(document).on('click', ".link-goal", function () {
        $.get('/goal/supervisor/'+$(this).data('id'), function (data) {
            $("#supervisorGoalModal").find('.data-placeholder').html(data);
            $("#supervisorGoalModal").modal('show');
        });
    });

    $(document).on('click', '.show-goal-detail', function(e) {
        $.get('/goal/library/'+$(this).data('id'), function (data) {
            $("#goal-detail-modal").find('.data-placeholder').html(data);
            $("#goal-detail-modal").modal('show');
        });
    });

    $(document).on('click', '.btn-link', function(e) {
        let linkedGoals = [];
        if(e.target.innerText == 'Link'){
            linkedGoals.push(e.target.getAttribute('data-id'));
            e.target.innerText = 'Unlink';
        }else{
            linkedGoals.pop(e.target.getAttribute('data-id'));
            e.target.innerText = 'Link';
        }
        $('#linked_goal_id').val(linkedGoals);
    });

    $(document).on('click', '.goal-change a', function (e) {
        const movingToPastMessage = "Changing the status of this goal will move it to your Past Goals tab. You can click there to make the goal active again at any time. Proceed?";
        const movingToCurrentMessage = "Changing the status of this goal will move it to your Current Goals tab. You can click there to access the goal again at any time. Proceed?";
        if($(this).data('current-status') === 'active' && !confirm(movingToPastMessage)) {
        e.preventDefault();
        } else if($(this).data('status') === 'active' && !confirm(movingToCurrentMessage)) {
        e.preventDefault();
        }
    });
    </script>
    </x-slot>

</x-side-layout>
