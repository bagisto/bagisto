@extends('admin::layouts.content')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                Categories
            </div>

            <div class="page-action">
                <a href="{{ route('admin.catalog.categories.create') }}" class="btn btn-lg btn-primary">
                    {{ __('Add Category') }}
                </a>
            </div>
        </div>

        <div class="page-content">

        </div>
    </div>
@stop