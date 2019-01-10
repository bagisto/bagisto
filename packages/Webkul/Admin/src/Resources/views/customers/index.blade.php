@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.customers.title') }}
@stop

@section('content')

    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.customers.customers.title') }}</h1>
            </div>
            <div class="page-action">
                {{-- <div class="export" @click="showModal('downloadDataGrid')">
                    <i class="export-icon"></i>
                    <span >
                        {{ __('admin::app.export.export') }}
                    </span>
                </div> --}}

                <a href="{{ route('admin.customer.create') }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.customers.customers.add-title') }}
                </a>
            </div>
        </div>


        <div class="page-content">
            @inject('customer','Webkul\Admin\DataGrids\CustomerDataGrid')
            {!! $customer->render() !!}
        </div>
    </div>

    {{-- <modal id="downloadDataGrid" :is-open="modalIds.downloadDataGrid">
        <h3 slot="header">{{ __('admin::app.export.download') }}</h3>
        <div slot="body">
            <export-form></export-form>
        </div>
    </modal> --}}

@stop

@push('scripts')

{{-- <script type="text/x-template" id="export-form-template">
    <form method="POST" action="{{ route('admin.datagrid.export') }}">

        <div class="page-content">
            <div class="form-container">
                @csrf()

                <input type="hidden" name="gridData" value="{{serialize($customer)}}">

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
</script> --}}

@endpush

