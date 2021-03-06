@extends('system.layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-3">
            @include('setup._sidebar-nav')
        </div>
        <div class="col-md-9">
        <div class="col-md-12 main-title-wrap">
            <span class="title">Admin User Edit</span>
        </div>
        <div class="col-md-12">
            {!! Form::bind($adminUser, ['method' => 'PUT', 'action' => route('setup.admin-user.update', $adminUser)]) !!}

            @include('user.admin-user._fields',['roles' => $roles])

            {!! Form::submit('Save') !!}

            {!! Form::close() !!}

        </div>
            </div>
    </div>

@endsection