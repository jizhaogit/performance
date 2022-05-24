@extends('sysadmin.layout', ['title' => 'Switch Identity'])
@section('tab-content')
<div>
    <p>
    <form  action="switch-identity" method="GET">
        <input name="search_user" placeholder="Search User" id="search_user" value="{{$search_user}}">
        <button class="btn btn-primary btn-sm" type="submit">Search</button>
    </form>
    </p>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered data-table">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@push('js')  
    <script>
        $(function () { 
        var user = $('#search_user').val();
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            ajax: {
                url: "{{ route('sysadmin.switch-identity') }}",
                data: {
                    "name": user
                }
            },
            columns: [
                
                {
                "data":"id",
                "render": function(data, type, row, meta){
                    if(type === 'display'){
                        data = '<a href="/sysadmin/switch-identity-action?new_user_id=' + data + '" class="edit btn btn-primary btn-sm">Switch</a>';
                        }
                    return data;
                    }
                },
                
                
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
            ]
        });
      });
    </script>    
    <script>
        $(document).ready(function(){
            $('[data-toggle="popover"]').popover(); 
        });
    </script>
@endpush