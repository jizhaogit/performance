@extends('sysadmin.layout')
@section('tab-content')

<div>
    <div class="h4 p-3">{{__('Excuse an employee')}}</div>
        <div class="p-3">
            <div class="row">
                <div class="col">
                    <p>All employees are required to complete a performance profile if they have worked more than 30 days within the Ministry's performance reporting cycle.  Employees may be excused from completing a profile <i>only</i> if they fit into one of the categories listed in the dropdown box below.</p>
                    <p><b>Note:</b>  Employees that show up incorrectly in your list can be changed by contacting AskMyHR to change the reporting relationship.</p>
                </div>
            </div>
        </div>
    <form action="" method="get" id="excuse_employees">
        <div class="h5 p-3">{{__('Step 1. Select employees to excuse')}}</div>
        <div class="card card-primary shadow mb-3" style="overflow-x: auto;">
            <div class="p-3">
            <div class="p-3">
                @include('sysadmin.partials.audiences')
            </div>
        </div>
    </div>
    <div class="h5 p-3">{{__('Step 2. Enter date range and reason for excusing selected employees')}}</div>
            <div class="card card-primary shadow mb-3" style="overflow-x:auto;width:1200px">
                <table class="uk-table m-3">
                <tbody>
                    <tr>
                        <td>
                            <b>From</b>
                        </td>
                        <td>
                        </td>
                        <td>
                            <b>To</b>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                            <b>Reason</b>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left; width: 300px; " class="p-1 form-group">
                            <input type="date" class="form-control" name="excused_start" value="{{request()->excused_start ?? ''}}">
                        </td>
                        <td style="text-align: left; width: 50px; " class="p-1 form-group">
                        </td>
                        <td style="text-align: left; width: 150px; " class="p-1 form-group">
                            <input type="radio" name="indefinite" value="indefinite"> Indefinite
                        </td>
                        <td style="text-align: left; width: 50px; " class="p-1 form-group">
                            <b>or</b>
                        </td>
                        <td style="text-align: left; width: 300px; " class="p-1 form-group">
                            <input type="date" class="form-control" name="excused_end" value="{{request()->excused_end ?? ''}}">
                        </td>
                        <td style="text-align: left; width: 50px; " class="p-1 form-group">
                        </td>
                        <td style="text-align: left; width: 300px; " class="p-1 form-group">
                            <x-dropdown :list="$reasons" class="multiple" name="excuse_reason" :selected="request()->excused_reason"></x-dropdown>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    <div class="h5 p-3">{{__('Step 3. Declaration')}}</div>
        <div class="card card-primary shadow mb-3" style="overflow-x: false;">
            <div class="p-3">
                <label for="confirm_declare" class="font-weight-normal" style="margin-left:25px;margin-left:25px;margin-top:20px;">
                    <input id="confirm_declare" type="checkbox" name="confirm_declare">&nbsp;&nbsp;I wish to excuse the selected employees from having to complete their MyPerformance Profile.
                </label>
            </div>
            <div class="p-3">
                <div class="alert alert-warning alert-dismissible no-border"  style="border-color:#d5e6f6; background-color:#d5e6f6" role="alert">
                <span class="h5" aria-hidden="true"><i class="icon fa fa-exclamation-triangle"></i>Note:  By doing so, these employees will not show up in current and historical performance reports.</span>
                </div>
            </div>
        </div>
        <div class="h5 p-3">{{__('Step 4. Execute selected employees or save and resume later')}}</div>
        <div class="p-3">
            <x-button type="button" size="sm" :tooltip="__('Excuse Employees')" tooltipPosition="bottom" class="mr-2" aria-label="Excuse Employees"> Excuse Employees</x-button>
        </div>
    </form>
</div>

<script type="text/javascript">
    $('body').popover({
        selector: '[data-toggle]',
        trigger: 'hover',
    });

    $(document).on('click', '.indefinite', function(e){
        e.preventDefault();

    });

    $('select[name="indefinite"]').on('change',function(e){
        console.log(this);
        var desc = $('option:selected', this).attr('data-desc');;
        console.log(desc);
        $('.goal_type_text').text(desc);
    });

</script>

@endsection