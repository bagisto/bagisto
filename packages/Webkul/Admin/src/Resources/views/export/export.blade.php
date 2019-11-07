<script type="text/x-template" id="export-form-template">
    <form method="POST" action="{{ route('admin.datagrid.export') }}" @submit.prevent="onSubmit">

        <div class="page-content">
            <div class="form-container">
                @csrf()

                <input type="hidden" name="gridName" value="{{ get_class($gridName) }}">

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

        <button type="submit" class="btn btn-lg btn-primary">
            {{ __('admin::app.export.export') }}
        </button>

    </form>
</script>

<script>
    Vue.component('export-form', {
        template: '#export-form-template',

        methods: {
            onSubmit: function(e) {
                e.target.submit();

                this.$root.$set(this.$root.modalIds, 'downloadDataGrid', false);
            }
        }
    });
</script>