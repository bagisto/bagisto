@extends('admin::layouts.content')

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                Attributes
            </div>

            <div class="page-action">
                <a href="{{ route('admin.catalog.attributes.create') }}" class="btn btn-lg btn-primary">
                    {{ __('Add Attribute') }}
                </a>
            </div>
        </div>

        <div class="page-content">

        </div>
    </div>
@stop