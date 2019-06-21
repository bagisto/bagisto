@extends('admin::layouts.content')

@section('page_title')
    {{ __('preorder::app.admin.preorders.title') }}
@stop

@section('content-wrapper')

    <div class="content full-page">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('preorder::app.admin.preorders.title') }}</h1>
            </div>

            <div class="page-action">
            </div>
        </div>

        <div class="page-content">

            {!! app('Webkul\SAASPreOrder\DataGrids\Admin\PreOrder')->render() !!}

        </div>
    </div>

@stop
