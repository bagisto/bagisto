<tbody>
    @foreach($records as $key => $record)
        <tr>
            <td>
                <span class="checkbox">
                    <input type="checkbox" v-model="dataIds" @change="select" :value="{{ $record->{$index} }}">

                    <label class="checkbox-view" for="checkbox1"></label>
                </span>
            </td>
            <?php $i = 0; ?>
            @foreach($record as $key1 => $column)
                @if($columns[$i]['type'] == 'boolean' && $columns[$i]['label'] == 'Status')
                    @if($column == 0)
                        <td>Inactive</td>
                    @else
                        <td>Active</td>
                    @endif
                @elseif($columns[$i]['type'] == 'boolean' && $columns[$i]['label'] != 'Status')
                    @if($column == 0)
                        <td>False</td>
                    @else
                        <td>True</td>
                    @endif
                @else
                    <td>{{ $column }}</td>
                @endif
                <?php $i++; ?>
            @endforeach

            <td style="width: 50px;">
                <div class="actions">
                    @foreach($actions as $action)
                        <a href="{{ route($action['route'], $record->{$index}) }}">
                            <span class="{{ $action['icon'] }}"></span>
                        </a>
                    @endforeach
                </div>
            </td>
        </tr>
    @endforeach
</tbody>