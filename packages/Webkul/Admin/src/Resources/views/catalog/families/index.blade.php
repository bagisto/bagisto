@extends('admin::layouts.content')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                {{ __('admin::app.catalog.families.families') }}
            </div>

            <div class="page-action">
                <a href="{{ route('admin.catalog.families.create') }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.catalog.families.add-family-btn-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
        
        </div>
    </div>
@stop