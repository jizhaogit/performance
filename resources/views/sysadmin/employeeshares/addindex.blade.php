<x-side-layout title="{{ __('Dashboard') }}">
    <div name="header" class="container-header p-n2 "> 
        <div class="container-fluid">
            <h3>Shared Employees</h3>
            @include('sysadmin.employeeshares.partials.tabs')
        </div>
    </div>
	<p class="px-3">Supervisors and Ministry Administrators may share an employee's PDP profile with another supervisor, 
		or staff who normally handle employees' permanent personnel records (ie. Public Service Agency) for a legitimate 
		business reason; such as shared supervisory duties.  An employee may wish to share their profile with someone 
		other than a direct supervisor (for example, a hiring manager).  In order to do this - <b>the employee's consent 
		is required</b>.</p>
	<p class="px-3">To continue, please use the functions below to select the employee profiles that you would like to share,
		the supervisor you would like to share the profiles with, which elements you would like to share, and your reason 
		for sharing the profile.</p>
	<form id="notify-form" action="{{ route('sysadmin.employeeshares.saveall') }}" method="post">
		@csrf

        <div class="container-fluid">
			<br>
			<h6 class="text-bold">Step 1. Select employees to share</h6>
			<br>
			<input type="hidden" id="selected_org_nodes" name="selected_org_nodes" value="">
			<input type="hidden" id="selected_emp_ids" name="selected_emp_ids" value="">
			{{-- <input type="hidden" id="selected_org_nodes" name="selected_org_nodes" value=""> --}}
			@include('sysadmin.employeeshares.partials.loader')

			<div class="p-3">
				<nav>
					<div class="nav nav-tabs" id="nav-tab" role="tablist">
						<a class="nav-item nav-link active" id="nav-list-tab" data-toggle="tab" href="#nav-list" role="tab" aria-controls="nav-list" aria-selected="true">List</a>
						<a class="nav-item nav-link" id="nav-tree-tab" data-toggle="tab" href="#nav-tree" role="tab" aria-controls="nav-tree" aria-selected="false">Tree</a>
					</div>
				</nav>
				<div class="tab-content" id="nav-tabContent">
					<div class="tab-pane fade show active" id="nav-list" role="tabpanel" aria-labelledby="nav-list-tab">
						@include('sysadmin.employeeshares.partials.recipient-list')
					</div>
					<div class="tab-pane fade" id="nav-tree" role="tabpanel" aria-labelledby="nav-tree-tab" loaded="">
						<div class="mt-2 fas fa-spinner fa-spin fa-3x fa-fw loading-spinner" id="tree-loading-spinner" role="status" style="display:none">
							<span class="sr-only">Loading...</span>
						</div>
					</div>
				</div>
			</div>
		</div>

        <div class="container-fluid">
			<br>
			<h6 class="text-bold">Step 2. Select who you would like to share the selected employee(s) with</h6> 
			<br>
			<input type="hidden" id="eselected_org_nodes" name="eselected_org_nodes" value="">
			<input type="hidden" id="eselected_emp_ids" name="eselected_emp_ids" value="">

			@include('sysadmin.employeeshares.partials.loader2')

			<div class="p-3">
				<nav>
					<div class="nav nav-tabs" id="enav-tab" role="tablist">
						<a class="nav-item nav-link active" id="enav-list-tab" data-toggle="tab" href="#enav-list" role="tab" aria-controls="enav-list" aria-selected="true">List</a>
						<a class="nav-item nav-link" id="enav-tree-tab" data-toggle="tab" href="#enav-tree" role="tab" aria-controls="enav-tree" aria-selected="false">Tree</a>
					</div>
				</nav>
				<div class="tab-content" id="enav-tabContent">
					<div class="tab-pane fade show active" id="enav-list" role="tabpanel" aria-labelledby="enav-list-tab">
						@include('sysadmin.employeeshares.partials.erecipient-list')
					</div>
					<div class="tab-pane fade" id="enav-tree" role="tabpanel" aria-labelledby="enav-tree-tab" loaded="">
						<div class="mt-2 fas fa-spinner fa-spin fa-3x fa-fw loading-spinner" id="etree-loading-spinner" role="status" style="display:none">
							<span class="sr-only">Loading...</span>
						</div>
					</div>
				</div>
			</div>
		</div>

        <div class="container-fluid">
			<br>
			<h6 class="text-bold">Step 3. Enter sharing details</h6> 
			<br>

			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col col-2">
							<label for='elements' title='Elements to Share Tooltip'>Elements to Share
								<select name="elements" class="form-control" id="elements">
									@foreach($sharedElements as $rid => $desc)
										<option value = {{ $rid }} > {{ $desc }} </option>
									@endforeach
								</select>
							</label>
						</div>
						<div class="col col-10">
							<x-input id="reason" name="reason" label="Reason for sharing" data-toggle="tooltip" data-placement="top" data-trigger="manual" tooltip="Reason tooltip"/>
						</div>
					</div>
				</div>
			</div>
		</div>

        <div class="container-fluid">
			<br>
			<h6 class="text-bold">Step 4. Select selected profile(s)</h6>
			<br>
			<div class="col-md-3 mb-2">
				<button class="btn btn-primary mt-2" type="submit" onclick="confirmSaveAllModal()" name="btn_send" value="btn_send">Share Profiles</button>
				<button class="btn btn-secondary mt-2">Cancel</button>
			</div>
		</div>

	</form>

	<h6 class="m-20">&nbsp;</h6>
	<h6 class="m-20">&nbsp;</h6>
	<h6 class="m-20">&nbsp;</h6>

	<!----modal starts here--->
	<div id="saveAllModal" class="modal" role='dialog'>
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
					<button class="btn btn-primary mt-2" type="submit" name="btn_send" value="btn_send">Share</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				</div>
				
			</div>
		</div>
	</div>
	<!--Modal ends here--->	

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

	<x-slot name="js">

		<script>
			let g_matched_employees = {!!json_encode($matched_emp_ids)!!};
			let g_selected_employees = {!!json_encode($old_selected_emp_ids)!!};
			let g_selected_orgnodes = {!!json_encode($old_selected_org_nodes)!!};
			let g_employees_by_org = [];

			let eg_matched_employees = {!!json_encode($ematched_emp_ids)!!};
			let eg_selected_employees = {!!json_encode($eold_selected_emp_ids)!!};
			let eg_selected_orgnodes = {!!json_encode($eold_selected_org_nodes)!!};
			let eg_employees_by_org = [];

			function confirmSaveAllModal(){
				$('#saveAllModal .modal-body p').html('Are you sure to share ?');
				$('#saveAllModal').modal();
			}

			$(document).ready(function() {

				$('#pageLoader').hide();

				$('#nav-tab').hide();
				$('#nav-tabContent').hide();
				$('#enav-tab').hide();
				$('#enav-tabContent').hide();

				$('#notify-form').keydown(function (e) {
					if (e.keyCode == 13) {
						e.preventDefault();
						return false;
					}
				});

				$('#enotify-form').keydown(function (e) {
					if (e.keyCode == 13) {
						e.preventDefault();
						return false;
					}
				});

				$('#notify-form').submit(function() {
					// assign back the selected employees to server
					var text = JSON.stringify(g_selected_employees);
					$('#selected_emp_ids').val( text );
					var text2 = JSON.stringify(g_selected_orgnodes);
					$('#selected_org_nodes').val( text2 );
					return true; // return false to cancel form action
				});

				$('#enotify-form').submit(function() {
					// assign back the selected employees to server
					var etext = JSON.stringify(eg_selected_employees);
					$('#eselected_emp_ids').val( text );
					var etext2 = JSON.stringify(eg_selected_orgnodes);
					$('#eselected_org_nodes').val( text2 );
					return true; // return false to cancel form action
				});


				// Tab  -- LIST Page  activate
				$("#nav-list-tab").on("click", function(e) {
					table  = $('#employee-list-table').DataTable();
					table.rows().invalidate().draw();
				});

				// Tab  -- LIST Page  activate
				$("#enav-list-tab").on("click", function(e) {
					table  = $('#eemployee-list-table').DataTable();
					table.rows().invalidate().draw();
				});

				// Tab  -- TREE activate
				$("#nav-tree-tab").on("click", function(e) {
					target = $('#nav-tree'); 
                    ddnotempty = $('#dd_level0').val() + $('#dd_level1').val() + $('#dd_level2').val() + $('#dd_level3').val() + $('#dd_level4').val();
                    if(ddnotempty) {
                        // To do -- ajax called to load the tree
                        if($.trim($(target).attr('loaded'))=='') {
                            $.when( 
                                $.ajax({
                                    url: '/sysadmin/employeeshares/org-tree',
                                    type: 'GET',
                                    data: $("#notify-form").serialize(),
                                    dataType: 'html',
                                    beforeSend: function() {
                                        $("#tree-loading-spinner").show();                    
                                    },
                                    success: function (result) {
                                        $(target).html(''); 
                                        $(target).html(result);

                                        $('#nav-tree').attr('loaded','loaded');
                                    },
                                    complete: function() {
                                        $(".tree-loading-spinner").hide();
                                    },
                                    error: function () {
                                        alert("error");
                                        $(target).html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
                                    }
                                })
                            ).then(function( data, textStatus, jqXHR ) {
                                //alert( jqXHR.status ); // Alerts 200
                                nodes = $('#accordion-level0 input:checkbox');
                                redrawTreeCheckboxes();	
                            }); 
                        } else {
                            redrawTreeCheckboxes();
                        }
                    } else {
						$(target).html('<i class="glyphicon glyphicon-info-sign"></i> Tree result is too big.  Please apply organization filter before clicking on Tree.');
					}
				});

				$("#enav-tree-tab").on("click", function(e) {
					etarget = $('#enav-tree'); 
                    ddnotempty = $('#edd_level0').val() + $('#edd_level1').val() + $('#edd_level2').val() + $('#edd_level3').val() + $('#edd_level4').val();
                    if(ddnotempty) {
                        // To do -- ajax called to load the tree
                        if($.trim($(etarget).attr('loaded'))=='') {
                            $.when( 
                                $.ajax({
                                    url: '/sysadmin/employeeshares/org-tree',
                                    type: 'GET',
                                    data: $("#enotify-form").serialize(),
                                    dataType: 'html',
                                    beforeSend: function() {
                                        $("#etree-loading-spinner").show();                    
                                    },
                                    success: function (result) {
                                        $(etarget).html(''); 
                                        $(etarget).html(result);

                                        $('#enav-tree').attr('loaded','loaded');
                                    },
                                    complete: function() {
                                        $(".etree-loading-spinner").hide();
                                    },
                                    error: function () {
                                        alert("error");
                                        $(etarget).html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
                                    }
                                })
                            ).then(function( data, textStatus, jqXHR ) {
                                //alert( jqXHR.status ); // Alerts 200
                                nodes = $('#eaccordion-level0 input:checkbox');
                                redrawTreeCheckboxes();	
                            }); 
                        } else {
                            redrawTreeCheckboxes();
                        }
                    } else {
						$(etarget).html('<i class="glyphicon glyphicon-info-sign"></i> Tree result is too big.  Please apply organization filter before clicking on Tree.');
					}
				});

				function redrawTreeCheckboxes() {
					// redraw the selection 
					nodes = $('#accordion-level0 input:checkbox');
					$.each( nodes, function( index, chkbox ) {
						if (g_employees_by_org.hasOwnProperty(chkbox.value)) {
							all_emps = g_employees_by_org[ chkbox.value ].map( function(x) {return x.employee_id} );
							b = all_emps.every(v=> g_selected_employees.indexOf(v) !== -1);
							if (all_emps.every(v=> g_selected_employees.indexOf(v) !== -1)) {
								$(chkbox).prop('checked', true);
								$(chkbox).prop("indeterminate", false);
							} else if (all_emps.some(v=> g_selected_employees.indexOf(v) !== -1)) {
								$(chkbox).prop('checked', false);
								$(chkbox).prop("indeterminate", true);
							} else {
								$(chkbox).prop('checked', false);
								$(chkbox).prop("indeterminate", false);
							}
						} 
					});

					// reset checkbox state
					reverse_list = nodes.get().reverse();
					$.each( reverse_list, function( index, chkbox ) {
						if (g_employees_by_org.hasOwnProperty(chkbox.value)) {
							pid = $(chkbox).attr('pid');
							do {
								value = '#orgCheck' + pid;
								toggle_indeterminate( value );
								pid = $('#orgCheck' + pid).attr('pid');    
							} 
							while (pid);
						}
					});
				}

				function eredrawTreeCheckboxes() {
					// redraw the selection 
					//console.log('eredraw triggered');
					enodes = $('#eaccordion-level0 input:checkbox');
					$.each( enodes, function( index, chkbox ) {
						if (eg_employees_by_org.hasOwnProperty(chkbox.value)) {
							eall_emps = eg_employees_by_org[ chkbox.value ].map( function(x) {return x.employee_id} );
							b = eall_emps.every(v=> eg_selected_orgnodes.indexOf(v) !== -1);

							if (eall_emps.every(v=> eg_selected_orgnodes.indexOf(v) !== -1)) {
								$(chkbox).prop('checked', true);
								$(chkbox).prop("indeterminate", false);
							} else if (eall_emps.some(v=> eg_selected_orgnodes.indexOf(v) !== -1)) {
								$(chkbox).prop('checked', false);
								$(chkbox).prop("indeterminate", true);
							} else {
								$(chkbox).prop('checked', false);
								$(chkbox).prop("indeterminate", false);
							}
						} else {
							if ( $(chkbox).attr('name') == 'userCheck[]') {
								if (eg_selected_orgnodes.includes(chkbox.value)) {
									$(chkbox).prop('checked', true);
								} else {
									$(chkbox).prop('checked', false);
								}
							}
						}
					});

					// reset checkbox state
					ereverse_list = enodes.get().reverse();
					$.each( ereverse_list, function( index, chkbox ) {
						if (eg_employees_by_org.hasOwnProperty(chkbox.value)) {
							pid = $(chkbox).attr('pid');
							do {
								value = '#eorgCheck' + pid;
								etoggle_indeterminate( value );
								pid = $('#eorgCheck' + pid).attr('pid');    
							} 
							while (pid);
						}
					});

				}

				// Set parent checkbox
				function toggle_indeterminate( prev_input ) {
					// Loop to checked the child
					var c_indeterminated = 0;
					var c_checked = 0;
					var c_unchecked = 0;
					prev_location = $(prev_input).parent().attr('href');
					nodes = $(prev_location).find("input:checkbox[name='orgCheck[]']");
					$.each( nodes, function( index, chkbox ) {
						if (chkbox.checked) {
							c_checked++;
						} else if ( chkbox.indeterminate ) {
							c_indeterminated++;
						} else {
							c_unchecked++;
						}
					});
					if (c_indeterminated > 0) {
						$(prev_input).prop('checked', false);
						$(prev_input).prop("indeterminate", true);
					} else if (c_checked > 0 && c_unchecked > 0) {
						$(prev_input).prop('checked', false);
						$(prev_input).prop("indeterminate", true);
					} else if (c_checked > 0 && c_unchecked == 0 ) {
						$(prev_input).prop('checked', true);
						$(prev_input).prop("indeterminate", false);
					} else {
						$(prev_input).prop('checked', false);
						$(prev_input).prop("indeterminate", false);
					}
				}

				// Set parent checkbox
				function etoggle_indeterminate( prev_input ) {
					// Loop to checked the child
					var c_indeterminated = 0;
					var c_checked = 0;
					var c_unchecked = 0;
					prev_location = $(prev_input).parent().attr('href');
					nodes = $(prev_location).find("input:checkbox[name='eorgCheck[]']");
					$.each( nodes, function( index, chkbox ) {
						if (chkbox.checked) {
							c_checked++;
						} else if ( chkbox.indeterminate ) {
							c_indeterminated++;
						} else {
							c_unchecked++;
						}
					});
					if (c_indeterminated > 0) {
						$(prev_input).prop('checked', false);
						$(prev_input).prop("indeterminate", true);
					} else if (c_checked > 0 && c_unchecked > 0) {
						$(prev_input).prop('checked', false);
						$(prev_input).prop("indeterminate", true);
					} else if (c_checked > 0 && c_unchecked == 0 ) {
						$(prev_input).prop('checked', true);
						$(prev_input).prop("indeterminate", false);
					} else {
						$(prev_input).prop('checked', false);
						$(prev_input).prop("indeterminate", false);
					}
				}

			});

			$(window).on('beforeunload', function(){
				$('#pageLoader').show();
			});

			$(window).resize(function(){
				location.reload();
				return;
			});

			// Model -- Confirmation Box

			// var modalConfirm = function(callback) {
			// 	$("#btn-confirm").on("click", function(){
			// 		$("#mi-modal").modal('show');
			// 	});
			// 	$("#modal-btn-si").on("click", function(){
			// 		callback(true);
			// 		$("#mi-modal").modal('hide');
			// 	});
				
			// 	$("#modal-btn-no").on("click", function(){
			// 		callback(false);
			// 		$("#mi-modal").modal('hide');
			// 	});
			// };

		</script>
	</x-slot>

</x-side-layout>