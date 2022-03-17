<script
type="text/x-template"
id="export-form-template"
>
    <form method="POST" action="{{ route('admin.datagrid.export', ['locale' => core()->getRequestedLocaleCode()]) }}" @submit.prevent="onSubmit">
        <div class="page-content">
            <div class="form-container">
                @csrf()

                <input type="hidden" name="datagridUrl" :value="datagridUrl">

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

        data: function() {
            return {
                id: btoa("{{ url()->current() }}"),
                datagridUrl: "{{ url()->current() }}"
            }
        },

        mounted: function() {
            this.datagridUrl = this.getCurrentDatagridInfo() ? this.getCurrentDatagridInfo()?.previousUrl : "{{ url()->current() }}";
        },

        methods: {
            onSubmit: function(e) {
                let self = this;

                e.target.submit();

                setTimeout(function() {
                    self.$root.$set(self.$root.modalIds, 'downloadDataGrid', false);
                }, 0);
            },

            getDatagridsInfoStorageKey: function() {
                return 'datagridsInfo';
            },

            getCurrentDatagridInfo: function() {
                let datagridsInfo = this.getDatagridsInfo();

                return this.isCurrentDatagridInfoExists() ?
                    datagridsInfo.find(({
                        id
                    }) => id === this.id) :
                    null;
            },

            getDatagridsInfo: function() {
                let storageInfo = localStorage.getItem(
                    this.getDatagridsInfoStorageKey()
                );

                return !this.isValidJsonString(storageInfo) ? [] :
                    JSON.parse(storageInfo) ?? [];
            },

            isValidJsonString: function(str) {
                try {
                    JSON.parse(str);
                } catch (e) {
                    return false;
                }
                return true;
            },

            isCurrentDatagridInfoExists: function() {
                let datagridsInfo = this.getDatagridsInfo();

                return !!datagridsInfo.find(({
                    id
                }) => id === this.id);
            },
        }
    });
</script>
