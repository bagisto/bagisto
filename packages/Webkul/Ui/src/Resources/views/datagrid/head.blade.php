<thead>
    <tr>
        <th class="grid_head" id="mastercheckbox" style="width: 50px;">
            <span class="checkbox">
                <input type="checkbox" id="mastercheckbox">
                <label class="checkbox-view" for="checkbox"></label>
            </span>
        </th>

        @foreach($columns as $key => $column)
            <th class="grid_head" data-column-alias="{{ $column['alias'] }}" data-column-name="{{ $column['column'] }}" data-column-sortable="{{ $column['sortable'] }}" data-column-type="{{ $column['type'] }}" style="width: {{ $column['width'] }}" v-on:click="sortCollection({{ $column['alias'] }})">{{ $column['label'] }}</th>
        @endforeach
    </tr>
</thead>