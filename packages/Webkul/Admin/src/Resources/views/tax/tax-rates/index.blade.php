@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.tax-rates.title') }}
@stop

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

                <a href="{{ route('admin.tax-rates.show') }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.settings.tax-rates.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('taxrates', 'Webkul\Admin\DataGrids\TaxRateDataGrid')
            {!! $taxrates->render() !!}
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

<script type="text/x-template" id="export-form-template">
    <form method="POST" action="{{ route('admin.datagrid.export') }}">

        <div class="page-content">
            <div class="form-container">
                @csrf()

                <?php
                    $data = json_encode((array) $taxrates);
                ?>

                <input type="hidden" name="gridData" value="{{ $data }}">
                <input type="hidden" name="file_name" value="Tax rates">

                <div class="control-group">
                    <label for="format" class="required">
                        {{ __('admin::app.export.format') }}
                    </label>
                    <select name="format" class="control" v-validate="'required'">
                        <option value="xls">{{ __('admin::app.export.xls') }}</option>
                        <option value="csv">{{ __('admin::app.export.csv') }}</option>
                    </select>
                </div>

            </div>
        </div>

        <button type="submit" class="btn btn-lg btn-primary" @click="closeModal">
            {{ __('admin::app.export.export') }}
        </button>

    </form>
</script>

<script>
    Vue.component('export-form', {
        template: '#export-form-template',
        methods: {
            closeModal () {
                this.$parent.closeModal();
            }
        }
    });
</script>

@endpush