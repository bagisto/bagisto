@extends('admin::layouts.content')

@section('page_title')

@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.users.users.title') }}</h1>
            </div>
            <div class="page-action">
                <a href="{{ route('admin.users.create') }}" class="btn btn-lg btn-primary">
                    {{ __('Add User') }}
                </a>
            </div>
        </div>

        <div class="page-content">

            {!! $datagrid->render() !!}
            {{-- <datetime></datetime> --}}
        </div>
    </div>

@stop
