<x-side-layout title="{{ __('Dashboard') }}">
    <div name="header" class="container-header p-n2 "> 
        <div class="container-fluid">
            <h3>Goal Bank</h3>
            @include('sysadmin.goalbank.partials.tabs')
        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="h5">{{__('Manage Goals in Goal Bank')}}</div>
            @include('sysadmin.goalbank.partials.filter')
            {{-- <p></p> --}}
            <div class="p-3">  
                <table class="table table-bordered filtertable" id="filtertable" style="width: 100%; overflow-x: auto; "></table>
            </div>
        </div>    
    </div>   

    <!----modal starts here--->
    <div id="deleteGoalModal" class="modal" role='dialog'>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure to send out this message ?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary mt-2" type="submit" name="btn_delete" value="btn_delete">Delete Goal</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
                
            </div>
        </div>
    </div>
    <!--Modal ends here--->	
	


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
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('js/bootstrap-multiselect.min.js')}} "></script>
    <script type="text/javascript">
        function confirmDeleteModal(){
            $('#saveGoalModal .modal-body p').html('Are you sure to delete goal?');
            $('#saveGoalModal').modal();
        }

        jq = jQuery.noConflict();
        jq(function( $ ) {
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
                        url: "{{ route('sysadmin.goalbank.managegetlist') }}",
                        data: function (d) 
                        {
                            // d.dd_level0 = $('#dd_level0').val();
                            // d.dd_level1 = $('#dd_level1').val();
                            // d.dd_level2 = $('#dd_level2').val();
                            // d.dd_level3 = $('#dd_level3').val();
                            // d.dd_level4 = $('#dd_level4').val();
                            // d.criteria = $('#criteria').val();
                            // d.search_text = $('#search_text').val();
                        }
                    },
                    columns: 
                    [
                        {title: 'Goal Title', ariaTitle: 'Goal Title', target: 0, type: 'string', data: 'title', name: 'title', searchable: true},
                        {title: 'Goal Type', ariaTitle: 'Goal Type', target: 0, type: 'string', data: 'goal_type_name', name: 'goal_type_name', searchable: false},
                        {title: 'Mandatory', ariaTitle: 'Mandatory', target: 0, type: 'string', data: 'mandatory', name: 'mandatory', searchable: false},
                        {title: 'Goal Creation Date', ariaTitle: 'Goal Creation Date', target: 0, type: 'date', data: 'created_at', name: 'goals.created_at', searchable: true},
                        {title: 'Created By', ariaTitle: 'Created By', target: 0, type: 'string', data: 'creator_name', name: 'creator_name', searchable: false},
                        {title: 'Audience', ariaTitle: 'Audience', target: 0, type: 'num', data: 'audience', name: 'audience', searchable: false},
                        {title: 'Action', ariaTitle: 'Action', target: 0, type: 'string', data: 'action', name: 'action', orderable: false, searchable: false},
                        {title: 'Goal ID', ariaTitle: 'Goal ID', target: 0, type: 'string', data: 'id', name: 'id', searchable: false, visible: false},
                    ]
                }
            );

        });

        $('#editModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var reason = button.data('reason');
            var role_id = parseInt(button.data('roleid'));
            var email = button.data('email');
            var model_id = button.data('modelid');
            var current = {{ auth()->user()->id }};
            $('#reason').val(reason);
            $('#accessselect').val(role_id);
            $('#model_id').val(model_id);
            $('#accessDetailLabel').text('Edit Employee Access Level:  '+email);
            $('#saveButton').prop('disabled', current == model_id);
            $('removeButton').prop('disabled', current == model_id);
            $('#accessselect').prop('disabled', current == model_id);
            $('#reason').prop('disabled', current == model_id);
            if($('#accessselect').val() == 4) {
                $('#accessselect').prop('disabled', true);
            }
            if($('#accessselect').val() == 3) {
                $('#admintable').show();
                var table = $('#admintable').DataTable
                (
                    {
                        processing: true,
                        serverSide: false,
                        scrollX: true,
                        stateSave: false,
                        deferRender: false,
                        ajax: {
                            type: 'GET',
                            url: "/sysadmin/goalbank/manageexistingaccessadmin/"+model_id,
                        },                        
                        columns: [
                            {title: 'Organization', ariaTitle: 'Organization', target: 0, type: 'string', data: 'organization', name: 'organization', searchable: true},
                            {title: 'Level 1', ariaTitle: 'Level 1', target: 0, type: 'string', data: 'level1_program', name: 'level1_program', searchable: true},
                            {title: 'Level 2', ariaTitle: 'Level 2', target: 0, type: 'string', data: 'level2_division', name: 'level2_division', searchable: true},
                            {title: 'Level 3', ariaTitle: 'Level 3', target: 0, type: 'string', data: 'level3_branch', name: 'level3_branch', searchable: true},
                            {title: 'Level 4', ariaTitle: 'Level 4', target: 0, type: 'string', data: 'level4', name: 'level4', searchable: true},
                            {title: 'User ID', ariaTitle: 'User ID', target: 0, type: 'num', data: 'user_id', name: 'user_id', searchable: false, visible: false},
                        ],  
                    }
                );
            } else {
                $('#admintable').hide();
            };
        });

        $('#editModal').on('hidden.bs.modal', function(event) {
            if($.fn.DataTable.isDataTable( '#admintable' )) {
                table = $('#admintable').DataTable();
                table.clear();
                table.draw();
            };
        });

        $('#cancelButton').on('click', function(event) {
            if($.fn.DataTable.isDataTable( '#admintable' )) {
                table = $('#admintable').DataTable();
                table.destroy();
            };
        });

        $('#removeButton').on('click', function(event) {
            console.log('Delete button clicked');
            var model_id = $('#model_id').val();
            var token = $('meta[name="csrf-token"]').attr('content');
            event.preventDefault();
            $.ajax ( {
                type: 'POST',
                url: 'manageexistingaccessdelete/'+model_id,
                data: {
                    'model_id':model_id,
                    '_token':token,
                    '_method':"DELETE",
                },
                success: function (result) {
                    window.location.href = 'manageexistingaccess';
                }
            });
        });

        $(window).on('beforeunload', function(){
            $('#pageLoader').show();
        });

        $(window).resize(function(){
            location.reload();
            return;
        });

    </script>
@endpush


</x-side-layout>