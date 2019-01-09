<tbody>
    @foreach($records as $key => $record)
        <tr>
            <?php $i=0 ?>
            @foreach($record as $key => $column)
                @if($i == 0)
                    <td>
                        <span class="checkbox">
                            <input type="checkbox" v-model="dataIds" @change="select" :value="{{ $column }}">
                            <label class="checkbox-view" for="checkbox1"></label>
                        </span>
                    </td>
                @endif

                @if($i > 0)
                    <td>{{ $column }}</td>
                @endif

                <?php $i++ ?>
            @endforeach

            <td style="width: 50px;">
                <div class="actions">
                    @foreach($actions as $action)
                        <a href="{{ route($action['route'], $record->id) }}">
                            <span class="{{ $action['icon'] }}"></span>
                        </a>
                    @endforeach
                </div>
            </td>
        </tr>
    @endforeach
</tbody>