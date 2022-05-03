<form action="manageexistingaccessupdate" method="post" enctype="multipart/form-data">
    {{ method_field('PUT') }}
    {{ csrf_field() }}
    <div class="modal fade editModal" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    {{-- <h5 class="modal-title" id="accessDetailLabel">Edit Employee Access Level</h5> --}}
                    <h5 class="modal-title" type="hidden" id="accessDetailLabel">Edit Employee Access Level</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="mt-4 p-3">
                    <div class="row">
                        <div class="col-12">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</p>
                        </div>
                    </div>
                </div>
                <div class="modal-body p-3">
                    <form id="access_form" action="" method="POST">
                        @csrf
                        <div class="row  p-3">
                            <div class="col col-4">
                                <input id="model_id" name="model_id" type="hidden" value="secret">
                                <label for='accessselect' title='Access Level Tooltip'>Access Level
                                <select name="accessselect" class="form-control" id="accessselect">
                                    @foreach($roles as $rid => $desc)
                                        <option value = {{ $rid }} > {{ $desc }} </option>
                                    @endforeach
                                </select>
                                </label>
                            </div>
                            <div class="col col-8">
                                <x-input id="reason" name="reason" label="Reason for assigning" data-toggle="tooltip" data-placement="top" data-trigger="hover-focus" tooltip="Reason tooltip"/>
                            </div>
                        </div>
                        <table class="table table-bordered admintable" id="admintable" style="width: 100%; overflow-x: auto; "></table>
                    </form>
                </div>
                <div class="modal-footer p-3">
                    <div class="col">
                        {{-- <form action="{{ route('sysadmin.access.manageexistingaccessdelete') }}" method="delete" enctype="multipart/form-data"> --}}
                        <form action="#" method="delete" enctype="multipart/form-data">
                            @method('DELETE')
                            @csrf
                            <button id="removeButton" name="removeButton" type="button" class="btn btn-outline-danger float-left" onClick="return confirm('Are you sure?')" aria-label="Remove Access">Remove Access</button>
                        </form>
                    </div>
                    <div class="col">
                        <button id="cancelButton" name="cancelButton" type="button" class="btn btn-secondary float-right" style="margin:5px;" data-dismiss="modal" aria-label="Cancel">Cancel</button>                    
                        <div class="space"></div>
                        <button id="saveButton" name="saveButton" type="submit" class="btn btn-primary float-right" onClick="return confirm('Are you sure?')" style="margin:5px;" role="save" data-toggle="modal" data-target="#updateModal" aria-label="Save Changes">Save Changes</button>                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

