<thead v-if="massActionsToggle == false">
    <tr style="height: 65px;">
        @if ($results['records'] instanceof \Illuminate\Pagination\LengthAwarePaginator && count($results['records']) && $results['enableMassActions'])
            <th class="grid_head" id="mastercheckbox" style="width: 50px;">
                <span class="checkbox">
                    <input type="checkbox" v-model="allSelected" v-on:change="selectAll">

                    <label class="checkbox-view" for="checkbox"></label>
                </span>
            </th>
        @endif

        @foreach($results['columns'] as $key => $column)
            <th class="grid_head"
                @if(isset($column['width']))
                    style="width: {{ $column['width'] }}"
                @endif

                @if(isset($column['sortable']) && $column['sortable'])
                    v-on:click="sortCollection('{{ $column['index'] }}')"
                @endif
            >
                {{ $column['label'] }}
            </th>
        @endforeach

        @if ($results['enableActions'])
            <th>
                {{ __('ui::app.datagrid.actions') }}
            </th>
        @endif
    </tr>
</thead>