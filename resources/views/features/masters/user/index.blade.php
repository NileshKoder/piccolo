@extends('layouts.app')

@section('title', 'Users')

@section('page-header', 'Users')

@section('breadcrumb')
<li class="breadcrumb-item active">Users</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            @include('features.common.alerts')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users mr-1"></i>
                        All Users
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('users.create') }}" target="_blank">
                            <button type="button" class="btn btn-primary float-right">
                                <i class="fas fa-plus"></i> Add User
                            </button>
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered table-hover" id="userDatatble">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('js/features/masters/user/index.js')}}"></script>
<script src="{{asset('js/sweetalert.min.js')}}"></script>
<script>
    initWareHouseDataTable("{{ route('users.getUsers') }}");
</script>
@endsection
