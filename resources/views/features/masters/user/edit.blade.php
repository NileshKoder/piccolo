@extends('layouts.app')

@section('title', 'Edit User')

@section('page-header', 'Edit User')

@section('styles')

@endsection

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
                        <i class="fas fa-edit mr-1"></i>
                        Edit User
                    </h3>
                </div>
                <form action="{{ route('users.update', $user->id) }}" method="post" id="editUser">
                    @csrf
                    {{ method_field('PUT')}}
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" value="{{ $user->name }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email address</label>
                                    <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" value="{{ $user->email }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="text" name="password" class="form-control" id="password" placeholder="Enter Password" value="{{ old('password') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="text" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Re-type Password" value="{{ old('password_confirmation') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Role</label>
                                    <select name="role" id="role" class="form-control">
                                        @foreach ($data['userRoles'] as $role)
                                        <option value="{{ $role }}" @if($user->role == $role) selected @endif>{{ $role }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer clearfix">
                        <button type="sumbit" class="btn btn-primary"><i class="fas fa-disk"></i> Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('js/features/masters/user/form.js')}}"></script>
<script>
    $(document).ready(function() {

    });
</script>
@endsection
