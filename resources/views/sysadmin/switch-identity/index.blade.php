@extends('sysadmin.layout', ['title' => 'Switch Identity'])
@section('tab-content')
    <div class="text-center mt-3">
        <h1>Switch Identity</h1>
        <p class="px-5 mt-2">

        </p>
    </div>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <section>
                <form name="search-form" action="{{ route('sysadmin.switch-identity') }}" method="GET">
                     <input type="text" name="username" id="username" value="{{ $username }}">    
                     <button type="submit" class="btn btn-primary pull-left">Search</button>                
                </form>    
            </section>
        </div>
    </div>
</div>
@if($count>0)
<p></p>
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2>Pick a user:</h2>
            <section>
                <table class="table" id="binds_table">
                    <thead>
                    <tr>
                        <th class="col-2">Action</th>
                        <th class="col-5">User</th>
                        <th class="col-5">Email</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user )
                        <tr > 
                            <td class="col-2">
                                <a href="/sysadmin/switch-identity?id={{ $user["id"] }}">Select</a>
                            </td>
                            <td class="col-5">
                                {{ $user["name"] }}
                            </td>
                            <td class="col-5">
                                {{ $user["email"] }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </div>
    </div>
</div>
@endif
@endsection
