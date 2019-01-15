<tbody>
    @if(count($records))
        @foreach($records as $key => $record)
            <tr>
                @if($enableMassActions)
                    <td>
                        <span class="checkbox">
                            <input type="checkbox" v-model="dataIds" @change="select" value="{{ $record->{$index} }}">

                            <label class="checkbox-view" for="checkbox"></label>
                        </span>
                    </td>
                @endif

                @foreach($columns as $column)
                    @php
                        $columnIndex = explode('.', $column['index']);

                        $columnIndex = end($columnIndex);
                    @endphp

                    @if(isset($column['wrapper']))
                        @if(isset($column['closure']) && $column['closure'] == true)
                            <td>{!! $column['wrapper']($record->{$columnIndex}) !!}</td>
                        @else
                            <td>{{ $column['wrapper']($record->{$columnIndex}) }}</td>
                        @endif
                    @else
                        <td>{{ $record->{$columnIndex} }}</td>
                    @endif
                @endforeach

                @if($enableActions)
                    <td style="width: 50px;">
                        <div class="actions">
                            @foreach($actions as $action)
                                <a href="{{ route($action['route'], $record->{$index}) }}">
                                    <span class="{{ $action['icon'] }}"></span>
                                </a>
                            @endforeach
                        </div>
                    </td>
                @endif
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="10" style="text-align: center;">{{$norecords}}</td>
        </tr>
    @endif
</tbody>