@extends('sysadmin.layout', ['title' => 'Switch Identity'])
@section('page-content')
    
    <div class="card">
        <div class="card-body p-n5 ">
            @include('sysadmin.employees.partials.filter')
            <div class="px-3 pt-2 pb-3"> 
                <table class="table table-bordered table-striped table-sm filtertable" id="filtertable" style="width: 100%; overflow-x: auto; "></table>
            </div>
        </div>    
    </div>   


    <x-slot name="css">
        <style>

            .select2-container .select2-selection--single {
                height: 38px !important;
            }EmployeeID

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 38px !important;
            }

            .pageLoader{
                /* background: url(../images/loader.gif) no-repeat center center; */
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                width: 100%;
                z-index: 9999999;
                background-color: #ffffff8c;
            }

            .pageLoader .spinner {
                /* background: url(../images/loader.gif) no-repeat center center; */
                position: fixed;
                top: 25%;
                left: 47%;
                /* height: 100%;
                width: 100%; */
                width: 10em;
                height: 10em;
                z-index: 9000000;
            }

        </style>
    </x-slot>

    @push('css')
        <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css" rel="stylesheet">
        <x-slot name="css">
            <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css" rel="stylesheet">
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
                #filtertable_filter label {
                    text-align: right !important;
                }
            </style>
        </x-slot>
    @endpush

    @push('js')
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
        <script src="https://code.jquery.com/jquery-3.6.0.js"></script>  
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
        <script type="text/javascript">
            $(document).ready()
            {
                $(function ()
                {
                    var table = $('.filtertable').DataTable 
                    (
                        {
                            processing: true,
                            serverSide: true,
                            scrollX: true,
                            stateSave: true,
                            deferRender: true,
                            ajax: 
                            {
                                url: "{{ route('sysadmin.identity-list') }}",
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
                            columns: 
                            [
                                
                                {
                                "data":"id",
                                "render": function(data, type, row, meta){
                                    if(type === 'display'){
                                        data = '<a href="/sysadmin/switch-identity-action?new_user_id=' + data + '" class="edit btn btn-primary btn-sm">Switch</a>';
                                        }
                                    return data;
                                    }
                                },
                                {title: 'Employee ID', ariaTitle: 'Employee ID', target: 0, type: 'string', data: 'employee_id', name: 'employee_demo.employee_id', searchable: true},
                                {title: 'Login Name', ariaTitle: 'Login Name', target: 0, type: 'string', data: 'name', name: 'users.name', searchable: true},
                                {title: 'Employee Name', ariaTitle: 'Employee Name', target: 0, type: 'string', data: 'employee_name', name: 'employee_demo.employee_name', searchable: true},
                                {title: 'Job Title', ariaTitle: 'Job Title', target: 0, type: 'string', data: 'job_title', name: 'employee_demo.job_title', searchable: true},
                                {title: 'Organization', ariaTitle: 'Organization', target: 0, type: 'string', data: 'organization', name: 'employee_demo.organization', searchable: true},
                                {title: 'Level 1', ariaTitle: 'Level 1', target: 0, type: 'string', data: 'level1_program', name: 'employee_demo.level1_program', searchable: true},
                                {title: 'Level 2', ariaTitle: 'Level 2', target: 0, type: 'string', data: 'level2_division', name: 'employee_demo.level2_division', searchable: true},
                                {title: 'Level 3', ariaTitle: 'Level 3', target: 0, type: 'string', data: 'level3_branch', name: 'employee_demo.level3_branch', searchable: true},
                                {title: 'Level 4', ariaTitle: 'Level 4', target: 0, type: 'string', data: 'level4', name: 'employee_demo.level4', searchable: true},
                                {title: 'Dept', ariaTitle: 'Dept', target: 0, type: 'string', data: 'deptid', name: 'employee_demo.deptid', searchable: true},
     
                            ],
                            'columnDefs': [ {
                                'targets': [0], // column index (start from 0)
                                'orderable': false, // set orderable false for selected columns
                             }],
                            "initComplete": function(settings, json ) {
                                table.columns.adjust().draw();
                            },
                            
                        }
                    );
                });
                
            }

            $(window).on('beforeunload', function(){
                    $('#pageLoader').show();
                });

            $(window).resize(function(){
                location.reload();
                // table.columns.adjust().draw();
                return;
            });

        </script>
    @endpush


@endsection

