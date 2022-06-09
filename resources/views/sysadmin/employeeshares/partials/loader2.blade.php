<div class="card p-3">
    <div class="form-row">
        <div class="form-group col-md-2">
        <label for="edd_level0">Organization</label>
        <select id="edd_level0" name="edd_level0" class="form-control select2">
            @if ( old('edd_level0') && session()->get('elevel0') )
                <option value="{{ session()->get('elevel0')->id }}">{{ session()->get('elevel0')->name }}</option>
            @endif
        </select>
        </div>
        <div class="form-group col-md-2">
        <label for="edd_level1">Level 1</label>
        <select id="edd_level1" name="edd_level1" class="form-control select2">
            @if ( old('edd_level1') && session()->get('elevel1') )
                <option value="{{ session()->get('elevel1')->id }}">{{ session()->get('elevel1')->name }}</option>
            @endif
        </select>
        </div>
        <div class="form-group col-md-2">
            <label for="edd_level2">Level 2</label>
            <select id="edd_level2" name="edd_level2" class="form-control select2">
                @if ( old('edd_level2') && session()->get('elevel2') )
                    <option value="{{ session()->get('elevel2')->id }}">{{ session()->get('elevel2')->name }}</option>
                @endif
            </select>
        </div>
        <div class="form-group col-md-2">
            <label for="edd_level3">Level 3</label>
            <select id="edd_level3" name="edd_level3" class="form-control select2">
                @if ( old('edd_level3') && session()->get('elevel3') )
                    <option value="{{ session()->get('elevel3')->id }}">{{ session()->get('elevel3')->name }}</option>
                @endif
            </select>
        </div>
        <div class="form-group col-md-2">
            <label for="edd_level4">Level 4</label>
            <select id="edd_level4" name="edd_level4" class="form-control select2">
                @if ( old('edd_level4') && session()->get('elevel4') )
                    <option value="{{ session()->get('elevel4')->id }}">{{ session()->get('elevel4')->name }}</option>
                @endif
            </select>
        </div>
        <div class="form-group col-md-2">
        </div>
        <div class="form-group col-md-2">
            <label for="ecriteria">Search Criteria</label>
            <select id="ecriteria" name="ecriteria" class="form-control">
                @foreach( $ecriteriaList as $key => $value )
                    <option value="{{ $key }}" {{  old('ecriteria') == $key ? 'selected' : '' }} >{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-2">
            <label for="esearch_text">Search Text</label>
            <input type="text" id="esearch_text" name="esearch_text" class="form-control" 
                value="{{ old('esearch_text') }}" placeholder="Search Text">
        </div>
        <div class="form-group col-md-2 p-3" style="text-align:left; vertical-align:bottom;">
            <div class="form-group row"> 
            </div>
            <div class="form-group row">
                <span class="float-left float-bottom">  
                    <button type="button" class="btn btn-primary" id="ebtn_search" name="ebtn_search">Filter</button>
                    <button type="button" class="btn btn-secondary" id="ebtn_search_reset" name="ebtn_search_reset" value="ebtn_reset">Reset</button>
                </span>
            </div>
        </div>
    </div>
</div>


@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
    .select2-selection--multiple{
        overflow: hidden !important;
        height: auto !important;
        min-height: 38px !important;
    }

    .select2-container .select2-selection--single {
        height: 38px !important;
        }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px !important;
    }

    </style>

@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>

        $(document).ready(function() {

            $('#ebtn_search').click(function(e) {
                e.preventDefault();
                console.log('#ebtn_search.click');
                $('#enav-tab').show();
				$('#enav-tabContent').show();
                var euser_selected = [];
                if($.fn.dataTable.isDataTable('#eemployee-list-table')) {
                    $('#eemployee-list-table').DataTable().clear();
                    $('#eemployee-list-table').DataTable().destroy();
                    $('#eemployee-list-table').empty();
                }
                $('#eemployee-list-table').DataTable( {
                    "scrollX": true,
                    retrieve: true,
                    "searching": false,
                    processing: true,
                    serverSide: true,
                    select: true,
                    'order': [[1, 'asc']],
                    ajax: {
                        url: '{{ route('sysadmin.employeeshares.eemployee.list') }}',
                        data: function (d) {
                            d.edd_level0 = $('#edd_level0').val();
                            d.edd_level1 = $('#edd_level1').val();
                            d.edd_level2 = $('#edd_level2').val();
                            d.edd_level3 = $('#edd_level3').val();
                            d.edd_level4 = $('#edd_level4').val();
                            d.ecriteria = $('#ecriteria').val();
                            d.esearch_text = $('#esearch_text').val();
                        }
                    },
                    "fnDrawCallback": function() {
                        list = ( $('#eemployee-list-table input:checkbox') );
                        $.each(list, function( index, item ) {
                            var index = $.inArray( item.value , eg_selected_employees);
                            if ( index === -1 ) {
                                $(item).prop('checked', false); // unchecked
                            } else {
                                $(item).prop('checked', true);  // checked 
                            }
                        });
                        // update the check all checkbox status 
                        if (eg_selected_employees.length == 0) {
                            $('#eemployee-list-select-all').prop("checked", false);
                            $('#eemployee-list-select-all').prop("indeterminate", false);   
                        } else if (eg_selected_employees.length == eg_matched_employees.length) {
                            $('#eemployee-list-select-all').prop("checked", true);
                            $('#eemployee-list-select-all').prop("indeterminate", false);   
                        } else {
                            $('#eemployee-list-select-all').prop("checked", false);
                            $('#eemployee-list-select-all').prop("indeterminate", true);    
                        }
                    },
                    "rowCallback": function( row, data ) {
                    },
                    columns: [
                        {title: '<input name="select_all" value="1" id="eemployee-list-select-all" type="checkbox" />', ariaTitle: 'eemployee-list-select-all', target: 0, type: 'string', data: 'eselect_users', name: 'eselect_users', orderable: false, searchable: false},
                        {title: 'ID', ariaTitle: 'ID', target: 0, type: 'string', data: 'eemployee_id', name: 'eemployee_id', className: 'dt-nowrap'},
                        {title: 'Name', ariaTitle: 'Name', target: 0, type: 'string', data: 'eemployee_name', name: 'eemployee_name', className: 'dt-nowrap'},
                        {title: 'Classification', ariaTitle: 'Classification', target: 0, type: 'string', data: 'ejobcode_desc', name: 'ejobcode_desc', className: 'dt-nowrap'},
                        {title: 'Email', ariaTitle: 'Email', target: 0, type: 'string', data: 'eemployee_email', name: 'eemployee_email', className: 'dt-nowrap'},
                        {title: 'Organization', ariaTitle: 'Organization', target: 0, type: 'string', data: 'eorganization', name: 'eorganization', className: 'dt-nowrap'},
                        {title: 'Level 1', ariaTitle: 'Level 1', target: 0, type: 'string', data: 'elevel1_program', name: 'elevel1_program', className: 'dt-nowrap'},
                        {title: 'Level 2', ariaTitle: 'Level 2', target: 0, type: 'string', data: 'elevel2_division', name: 'elevel2_division', className: 'dt-nowrap'},
                        {title: 'Level 3', ariaTitle: 'Level 3', target: 0, type: 'string', data: 'elevel3_branch', name: 'elevel3_branch', className: 'dt-nowrap'},
                        {title: 'Level 4', ariaTitle: 'Level 4', target: 0, type: 'string', data: 'elevel4', name: 'elevel4', className: 'dt-nowrap'},
                        {title: 'Dept', ariaTitle: 'Dept', target: 0, type: 'string', data: 'edeptid', name: 'edeptid', className: 'dt-nowrap'},
                    ],
                });

            });

            $('#edd_level0').change(function (e){
                e.preventDefault();
                console.log('#edd_level0.change');
            });

            $('#edd_level1').change(function (e){
                e.preventDefault();
                console.log('#edd_level1.change');
            });

            $('#edd_level2').change(function (e){
                e.preventDefault();
                console.log('#edd_level2.change');
            });

            $('#edd_level3').change(function (e){
                e.preventDefault();
                console.log('#edd_level3.change');
            });

            $('#edd_level4').change(function (e){
                e.preventDefault();
                console.log('#edd_level4.change');
                $('#ebtn_search').click();
            });

            $('#ecriteria').change(function (e){
                e.preventDefault();
                console.log('#ecriteria.change');
                $('#ebtn_search').click();
            });

            $('#esearch_text').change(function (e){
                e.preventDefault();
                console.log('#esearch_text.change');
               $('#ebtn_search').click();
            });

            $('#esearch_text').keydown(function (e){
                if (e.keyCode == 13) {
                    e.preventDefault();
                    console.log('#esearch_text.keydown');
                    $('#ebtn_search').click();
                }
            });

            $('#ebtn_search_reset').click(function (e){
                e.preventDefault();
                console.log('#ebtn_search_reset.click');
                $('#ecriteria').val('all');
                $('#esearch_text').val(null);
                $('#edd_level0').val(null).trigger('change');
                $('#edd_level1').val(null).trigger('change');
                $('#edd_level2').val(null).trigger('change');
                $('#edd_level3').val(null).trigger('change');
                $('#edd_level4').val(null).trigger('change');
            });

            $('#edd_level0').select2({
                placeholder: 'Select Organization',
                allowClear: true,
                ajax: {
                    url: '/sysadmin/employeeshares/eorg-organizations'
                    , dataType: 'json'
                    , delay: 250
                    , data: function(params) {
                        var query = {
                            'q': params.term
                        , }
                        return query;
                    }
                    , processResults: function(data) {
                        return {
                            results: data
                            };
                    }
                    , cache: false
                }
            });

            $('#edd_level1').select2({
                placeholder: 'Select Level 1',
                allowClear: true,
                ajax: {
                    url: '/sysadmin/employeeshares/eorg-programs' 
                    , dataType: 'json'
                    , delay: 250
                    , data: function(params) {
                        var query = {
                            'q': params.term,
                            'elevel0': $('#edd_level0').children("option:selected").val()
                        , }
                        return query;
                    }
                    , processResults: function(data) {
                        return {
                            results: data
                            };
                    }
                    , cache: false
                }
            });

            $('#edd_level2').select2({
                placeholder: 'Select Level 2',
                allowClear: true,
                ajax: {
                    url: '/sysadmin/employeeshares/eorg-divisions' 
                    , dataType: 'json'
                    , delay: 250
                    , data: function(params) {
                        var query = {
                            'q': params.term,
                            'elevel0': $('#edd_level0').children("option:selected").val(),
                            'elevel1': $('#edd_level1').children("option:selected").val()
                        , }
                        return query;
                    }
                    , processResults: function(data) {
                        return {
                            results: data
                            };
                    }
                    , cache: false
                }
            });

            $('#edd_level3').select2({
                placeholder: 'Select Level 3',
                allowClear: true,
                ajax: {
                    url: '/sysadmin/employeeshares/eorg-branches' 
                    , dataType: 'json'
                    , delay: 250
                    , data: function(params) {
                        var query = {
                            'q': params.term,
                            'elevel0': $('#edd_level0').children("option:selected").val(),
                            'elevel1': $('#edd_level1').children("option:selected").val(),
                            'elevel2': $('#edd_level2').children("option:selected").val()
                        , }
                        return query;
                    }
                    , processResults: function(data) {
                        return {
                            results: data
                            };
                    }
                    , cache: false
                }
            });

            $('#edd_level4').select2({
                placeholder: 'Select Level 4',
                allowClear: true,
                ajax: {
                    url: '/sysadmin/employeeshares/eorg-level4' 
                    , dataType: 'json'
                    , delay: 250
                    , data: function(params) {
                        var query = {
                            'q': params.term,
                            'elevel0': $('#edd_level0').children("option:selected").val(),
                            'elevel1': $('#edd_level1').children("option:selected").val(),
                            'elevel2': $('#edd_level2').children("option:selected").val(),
                            'elevel3': $('#edd_level3').children("option:selected").val()
                        , }
                        return query;
                    }
                    , processResults: function(data) {
                        return {
                            results: data
                            };
                    }
                    , cache: false
                }
            });
            
            $('#edd_level0').on('select2:select', function (e) {
                e.preventDefault();
                console.log('#edd_level0.select2:select');
                $('#edd_level1').val(null).trigger('change');
                $('#edd_level2').val(null).trigger('change');
                $('#edd_level3').val(null).trigger('change');
                $('#edd_level4').val(null).trigger('change');
            });

            $('#edd_level1').on('select2:select', function (e) {
                e.preventDefault();
                console.log('#edd_level1.select2:select');
                $('#edd_level2').val(null).trigger('change');
                $('#edd_level3').val(null).trigger('change');
                $('#edd_level4').val(null).trigger('change');
            });

            $('#edd_level2').on('select2:select', function (e) {
                e.preventDefault();
                console.log('#edd_level2.select2:select');
                $('#edd_level3').val(null).trigger('change');
                $('#edd_level4').val(null).trigger('change');
            });

            $('#edd_level3').on('select2:select', function (e) {
                e.preventDefault();
                console.log('#edd_level3.select2:select');
                $('#edd_level4').val(null).trigger('change');
            });

            $('#edd_level4').on('select2:select', function (e) {
                e.preventDefault();
                console.log('#edd_level4.select2:select');
            });

            $('#edd_level0').on('select2:unselect', function (e) {
                e.preventDefault();
                console.log('#edd_level0.select2:unselect');
                $('#edd_level0').val(null).trigger('change');
                $('#edd_level1').val(null).trigger('change');
                $('#edd_level2').val(null).trigger('change');
                $('#edd_level3').val(null).trigger('change');
                $('#edd_level4').val(null).trigger('change');
            });

            $('#edd_level1').on('select2:unselect', function (e) {
                e.preventDefault();
                console.log('#edd_level1.select2:unselect');
                $('#edd_level1').val(null).trigger('change');
                $('#edd_level2').val(null).trigger('change');
                $('#edd_level3').val(null).trigger('change');
                $('#edd_level4').val(null).trigger('change');
            });

            $('#edd_level2').on('select2:unselect', function (e) {
                e.preventDefault();
                console.log('#edd_level2.select2:unselect');
                $('#edd_level2').val(null).trigger('change');
                $('#edd_level3').val(null).trigger('change');
                $('#edd_level4').val(null).trigger('change');
            });

            $('#edd_level3').on('select2:unselect', function (e) {
                e.preventDefault();
                console.log('#edd_level3.select2:unselect');
                $('#edd_level3').val(null).trigger('change');
                $('#edd_level4').val(null).trigger('change');
            });

            $('#edd_level4').on('select2:unselect', function (e) {
                e.preventDefault();
                console.log('#edd_level4.select2:unselect');
                $('#edd_level4').val(null).trigger('change');
                $('#ebtn_search').click();
            });

        });

    </script>

@endpush