@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.tax-rates.edit-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.tax-rates.update', $taxRate->id) }}" @submit.prevent="onSubmit">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('admin::app.settings.tax-rates.edit.title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.settings.tax-rates.edit-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <catalog-rule></catalog-rule>
            </div>
        </form>
    </div>
@stop