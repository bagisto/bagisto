@extends('admin::layouts.content')

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.catalog.products.store') }}" @submit.prevent="onSubmit">

            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('admin::app.catalog.products.add-title') }}</h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.catalog.products.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                @csrf()


            </div>

        </form>
    </div>
@stop