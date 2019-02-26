<tbody>
    @if (count($records))
        @foreach ($records as $key => $record)
            <tr>
                @if ($enableMassActions)
                    <td>
                        <span class="checkbox">
                            <input type="checkbox" v-model="dataIds" @change="select" value="{{ $record->{$index} }}">

                            <label class="checkbox-view" for="checkbox"></label>
                        </span>
                    </td>
                @endif

                @foreach ($columns as $column)
                    @php
                        $columnIndex = explode('.', $column['index']);

                        $columnIndex = end($columnIndex);
                    @endphp

                    @if (isset($column['wrapper']))
                        @if (isset($column['closure']) && $column['closure'] == true)
                            <td data-value="{{ $column['label'] }}">{!! $column['wrapper']($record) !!}</td>
                        @else
                            <td data-value="{{ $column['label'] }}">{{ $column['wrapper']($record) }}</td>
                        @endif
                    @else
                        @if ($column['type'] == 'price')
                            @if (isset($column['currencyCode']))
                                <td data-value="{{ $column['label'] }}">{{ core()->formatPrice($record->{$columnIndex}, $column['currencyCode']) }}</td>
                            @else
                                <td data-value="{{ $column['label'] }}">{{ core()->formatBasePrice($record->{$columnIndex}) }}</td>
                            @endif
                        @else
                            <td data-value="{{ $column['label'] }}">{{ $record->{$columnIndex} }}</td>
                        @endif
                    @endif
                @endforeach

                @if ($enableActions)
                    <td class="actions" style="width: 100px;" data-value=" {{ __('ui::app.datagrid.actions') }}">
                        <div>
                            @foreach ($actions as $action)
                                <a href="{{ route($action['route'], $record->{$index}) }}">
                                    <span class="{{ $action['icon'] }}" onclick="return confirm('{{ __('ui::app.datagrid.click_on_action') }}')"></span>
                                </a>
                            @endforeach
                        </div>
                    </td>
                @endif
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="10" style="text-align: center;">{{ $norecords }}</td>
        </tr>
    @endif
</tbody>