@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.configuration.sales.general.title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="" @submit.prevent="onSubmit">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        {{ __('admin::app.configuration.sales.general.title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.configuration.sales.general.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">

                <div class="form-container">

                </div>

            </div>
        </form>
    </div>
@stop