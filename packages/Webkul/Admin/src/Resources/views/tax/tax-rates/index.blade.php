@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.tax-rates.title') }}
@stop

@push('css')
    <style>
        @media only screen and (max-width: 450px){
            .content-container .content .page-header .page-title{
                width: 100% !important;
            }

            .content-container .content .page-header .page-action {
                margin-top: 0px !important;
                float: left;
            }

            .export-import {
                margin-top: 0px !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.settings.tax-rates.title') }}</h1>
            </div>

            <div class="page-action">
                <div class="export-import" @click="showModal('uploadDataGrid')" style="margin-right: 20px;">
                    <i class="import-icon"></i>

                    <span>
                        {{ __('admin::app.export.import') }}
                    </span>
                </div>

                <div class="export-import" @click="showModal('downloadDataGrid')">
                    <i class="export-icon"></i>

                    <span>
                        {{ __('admin::app.export.export') }}
                    </span>
                </div>
                @if (bouncer()->hasPermission('settings.taxes.tax-rates.create'))
                    <a href="{{ route('admin.tax-rates.create') }}" class="btn btn-lg btn-primary">
                        {{ __('admin::app.settings.tax-rates.add-title') }}
                    </a>
                @endif
            </div>
        </div>

        <div class="page-content">
            <datagrid-plus src="{{ route('admin.tax-rates.index') }}"></datagrid-plus>
        </div>
    </div>

    <modal id="downloadDataGrid" :is-open="modalIds.downloadDataGrid">
        <h3 slot="header">{{ __('admin::app.export.download') }}</h3>

        <div slot="body">
            <export-form></export-form>
        </div>
    </modal>

    <modal id="uploadDataGrid" :is-open="modalIds.uploadDataGrid">
        <h3 slot="header">{{ __('admin::app.export.upload') }}</h3>

        <div slot="body">
            <form method="POST" action="{{ route('admin.tax-rates.import') }}" enctype="multipart/form-data" @submit.prevent="onSubmit">
                @csrf()

                <div class="control-group" :class="[errors.has('file') ? 'has-error' : '']">
                    <label for="file" class="required">{{ __('admin::app.export.file') }}</label>

                    <input v-validate="'required'" type="file" class="control" id="file" name="file" data-vv-as="&quot;{{ __('admin::app.export.file') }}&quot;" value="{{ old('file') }}"/ style="padding-top: 5px">

                    <span>{{ __('admin::app.export.allowed-type') }}</span>

                    <span><b>{{ __('admin::app.export.file-type') }}</b></span>

                    <span class="control-error" v-if="errors.has('file')">@{{ errors.first('file') }}</span>
                </div>

                <button type="submit" class="btn btn-lg btn-primary">
                    {{ __('admin::app.export.import') }}
                </button>
            </form>
        </div>
    </modal>
@endsection

@push('scripts')
    @include('admin::export.export', ['gridName' => app('Webkul\Admin\DataGrids\TaxRateDataGrid')])
@endpush
