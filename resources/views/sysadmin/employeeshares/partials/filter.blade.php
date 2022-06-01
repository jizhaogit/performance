<div class="card p-3">
    <div class="form-row">
        <div class="form-group col-md-2">
            <label for="dd_level0">Organization</label>
            <select id="dd_level0" name="dd_level0" class="form-control select2">
                @if ( old('dd_level0') && session()->get('level0') )
                    <option value="{{ session()->get('level0')->id }}">{{ session()->get('level0')->name }}</option>
                @endif
            </select>
        </div>
        <div class="form-group col-md-2">
            <label for="dd_level1">Level 1</label>
            <select id="dd_level1" name="dd_level1" class="form-control select2">
                @if ( old('dd_level1') && session()->get('level1') )
                    <option value="{{ session()->get('level1')->id }}">{{ session()->get('level1')->name }}</option>
                @endif
            </select>
        </div>
        <div class="form-group col-md-2">
            <label for="dd_level2">Level 2</label>
            <select id="dd_level2" name="dd_level2" class="form-control select2">
                @if ( old('dd_level2') && session()->get('level2') )
                    <option value="{{ session()->get('level2')->id }}">{{ session()->get('level2')->name }}</option>
                @endif
            </select>
        </div>
        <div class="form-group col-md-2">
            <label for="dd_level3">Level 3</label>
            <select id="dd_level3" name="dd_level3" class="form-control select2">
                @if ( old('dd_level3') && session()->get('level3') )
                    <option value="{{ session()->get('level3')->id }}">{{ session()->get('level3')->name }}</option>
                @endif
            </select>
        </div>
        <div class="form-group col-md-2">
            <label for="dd_level4">Level 4</label>
            <select id="dd_level4" name="dd_level4" class="form-control select2">
                @if ( old('dd_level4') && session()->get('level4') )
                    <option value="{{ session()->get('level4')->id }}">{{ session()->get('level4')->name }}</option>
                @endif
            </select>
        </div>
        <div class="form-group col-md-2">
        </div>
        <div class="form-group col-md-2">
            <label for="criteria">Search Criteria</label>
            <select id="criteria" name="criteria" class="form-control">
                @foreach( $criteriaList as $key => $value )
                    <option value="{{ $key }}" {{  old('criteria') == $key ? 'selected' : '' }} >{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-2">
            <label for="search_text">Search Text</label>
            <input type="text" id="search_text" name="search_text" class="form-control" 
                    value="{{ old('search_text') }}" placeholder="Search Text">
        </div>
        <div class="form-group col-md-2 p-3 float-left float-bottom" style="display: flex; flex-direction: column;">
            <div class="form-group row"> </div>
                <div class="form-group row">
                    <span class="float-left float-bottom">  
                        <button type="button" class="btn btn-primary" id="btn_search" name="btn_search" value="button" >Filter</button>
                        <button type="button" class="btn btn-secondary  " id="btn_search_reset" name="btn_search_reset" value="btn_reset">Reset</button>
                    </span>
                </div>
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
        $('#dd_level0').select2({
            placeholder: 'Select Organization',
            allowClear: true,
            ajax: {
                url: '/sysadmin/employeeshares/org-organizations'
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

        $('#dd_level1').select2({
            placeholder: 'Select Level 1',
            allowClear: true,
            ajax: {
                url: '/sysadmin/employeeshares/org-programs' 
                , dataType: 'json'
                , delay: 250
                , data: function(params) {
                    var query = {
                        'q': params.term,
                        'level0': $('#dd_level0').children("option:selected").val()
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

        $('#dd_level2').select2({
            placeholder: 'Select Level 2',
            allowClear: true,
            ajax: {
                url: '/sysadmin/employeeshares/org-divisions' 
                , dataType: 'json'
                , delay: 250
                , data: function(params) {
                    var query = {
                        'q': params.term,
                        'level0': $('#dd_level0').children("option:selected").val(),
                        'level1': $('#dd_level1').children("option:selected").val()
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

        $('#dd_level3').select2({
            placeholder: 'Select Level 3',
            allowClear: true,
            ajax: {
                url: '/sysadmin/employeeshares/org-branches' 
                , dataType: 'json'
                , delay: 250
                , data: function(params) {
                    var query = {
                        'q': params.term,
                        'level0': $('#dd_level0').children("option:selected").val(),
                        'level1': $('#dd_level1').children("option:selected").val(),
                        'level2': $('#dd_level2').children("option:selected").val()
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

        $('#dd_level4').select2({
            placeholder: 'Select level 4',
            allowClear: true,
            ajax: {
                url: '/sysadmin/employeeshares/org-level4' 
                , dataType: 'json'
                , delay: 250
                , data: function(params) {
                    var query = {
                        'q': params.term,
                        'level0': $('#dd_level0').children("option:selected").val(),
                        'level1': $('#dd_level1').children("option:selected").val(),
                        'level2': $('#dd_level2').children("option:selected").val(),
                        'level3': $('#dd_level3').children("option:selected").val()
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
        
        $('#dd_level0').on('select2:select', function (e) {
            // Do something
            $('#dd_level1').val(null).trigger('change');
            $('#dd_level2').val(null).trigger('change');
            $('#dd_level3').val(null).trigger('change');
            $('#dd_level4').val(null).trigger('change');
        });

        $('#dd_level1').on('select2:select', function (e) {
            // Do something
            $('#dd_level2').val(null).trigger('change');
            $('#dd_level3').val(null).trigger('change');
            $('#dd_level4').val(null).trigger('change');
        });

        $('#dd_level2').on('select2:select', function (e) {
            // Do something
            $('#dd_level3').val(null).trigger('change');
            $('#dd_level4').val(null).trigger('change');
        });

        $('#dd_level3').on('select2:select', function (e) {
            // Do something
            $('#dd_level4').val(null).trigger('change');
        });

        $('#btn_search_reset').click(function() {
            $('#dd_level0').val(null).trigger('change');
            $('#dd_level1').val(null).trigger('change');
            $('#dd_level2').val(null).trigger('change');
            $('#dd_level3').val(null).trigger('change');
            $('#dd_level4').val(null).trigger('change');
            $('#search_text').val(null);
        });



    </script>

@endpush