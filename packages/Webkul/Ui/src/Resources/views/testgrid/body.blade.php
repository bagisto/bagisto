<tbody>
    @foreach($records as $key => $record)
        <tr>
            <td>
                <span class="checkbox">
                    <input type="checkbox" v-model="dataIds" @change="select" :value="{{ $record->{$index} }}">

                    <label class="checkbox-view" for="checkbox1"></label>
                </span>
            </td>

            @foreach($columns as $column)
                @php
                    $index = explode('.', $column['index']);

                    $index = end($index);
                @endphp

                @if(isset($column['wrapper']))
                    @if(isset($column['closure']) && $column['closure'] == true)
                        <td>{!! $column['wrapper']($record->{$index}) !!}</td>
                    @else
                        <td>{{ $column['wrapper']($record->{$index}) }}</td>
                    @endif
                @else
                    <td>{{ $record->{$index} }}</td>
                @endif
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