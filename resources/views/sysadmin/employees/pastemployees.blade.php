@extends('sysadmin.layout')
@section('tab-content')
<div class="col-md-8"> @include('sysadmin.employees.partials.tabs')</div>

<div class="card">
	<div class="card-body">
        <div class="h4">{{__('Past Employees')}}</div>
		{{-- @include('sysadmin.employees.partials.filter2') --}}
		@include('sysadmin.partials.filter')
		<p></p>
        <table class="table table-bordered pasttable" id="pasttable" style="width: 100%; overflow-x: auto; "></table>
	</div>    
</div>   
@endsection


@push('css')
    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <x-slot name="css">
        <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap4.min.css" rel="stylesheet">
        <style>
            .text-truncate-30 {
                white-space: wrap; 
                overflow: hidden;
                text-overflow: ellipsis;
                width: 30em;
            }
        
            .text-truncate-10 {
                white-space: wrap; 
                overflow: hidden;
                text-overflow: ellipsis;
                width: 5em;
            }
            #pasttable_filter label {
                text-align: right !important;
            }
        </style>
    </x-slot>
    
@endpush
@push('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript">
        $(document).ready()
        {
            $(function ()
            {
                var table = $('.pasttable').DataTable
                (
                    {
                        processing: true,
                        serverSide: true,
                        scrollX: true,
                        stateSave: true,
                        deferRender: true,
                        ajax: 
                        {
                            url: "{{ route('sysadmin.employees.pastemployeeslist') }}",
                            data: function (d) 
                            {
                                d.dd_level0 = $('#dd_level0').val();
                                d.dd_level1 = $('#dd_level1').val();
                                d.dd_level2 = $('#dd_level2').val();
                                d.dd_level3 = $('#dd_level3').val();
                                d.dd_level4 = $('#dd_level4').val();
                                d.criteria = $('#criteria').val();
                                d.search_text = $('#search_text').val();
                            }
                        },
                        "rowCallback": function( row, data ) 
                        {
                        },
                        columns: 
                        [
                            {title: 'ID', ariaTitle: 'ID', target: 0, type: 'string', data: 'employee_id', name: 'employee_demo.employee_id', searchable: true},
                            {title: 'Name', ariaTitle: 'Name', target: 0, type: 'string', data: 'employee_name', name: 'employee_demo.employee_name', searchable: true},
                            {title: 'Job Title', ariaTitle: 'Job Title', target: 0, type: 'string', data: 'job_title', name: 'employee_demo.job_title', searchable: true},
                            {title: 'Organization', ariaTitle: 'Organization', target: 0, type: 'string', data: 'organization', name: 'employee_demo.organization', searchable: true},
                            {title: 'Level 1', ariaTitle: 'Level 1', target: 0, type: 'string', data: 'level1_program', name: 'employee_demo.level1_program', searchable: true},
                            {title: 'Level 2', ariaTitle: 'Level 2', target: 0, type: 'string', data: 'level2_division', name: 'employee_demo.level2_division', searchable: true},
                            {title: 'Level 3', ariaTitle: 'Level 3', target: 0, type: 'string', data: 'level3_branch', name: 'employee_demo.level3_branch', searchable: true},
                            {title: 'Level 4', ariaTitle: 'Level 4', target: 0, type: 'string', data: 'level4', name: 'employee_demo.level4', searchable: true},
                            {title: 'Dept', ariaTitle: 'Dept', target: 0, type: 'string', data: 'deptid', name: 'employee_demo.deptid', searchable: true},
                            {title: 'User ID', ariaTitle: 'User ID', target: 0, type: 'num', data: 'id', name: 'users.id', searchable: true, visible: false},
                        ]
                    }
                );
            });
        }

        $(window).on('beforeunload', function(){
				$('#pageLoader').show();
			});

        $(window).resize(function(){
            location.reload();
            return;
        });

    </script>
@endpush



