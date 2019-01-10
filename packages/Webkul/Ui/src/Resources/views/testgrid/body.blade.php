<tbody>
    @foreach($records as $key => $record)
        {{-- {{ dd($record) }} --}}

        <tr>
            <td>
                <span class="checkbox">
                    <input type="checkbox" v-model="dataIds" @change="select" :value="{{ $record->{$index} }}">

                    <label class="checkbox-view" for="checkbox1"></label>
                </span>
            </td>

            @foreach($record as $column)
                @if(isset($columns[$key]))
                    @if(isset($columns[$key]['wrapper']))

                    @endif
                @endif
                <td>{{ $column }}</td>
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