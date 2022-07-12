<tbody>
    @if ($records instanceof \Illuminate\Pagination\LengthAwarePaginator && count($records))
        @foreach ($records as $key => $record)
            <tr>
                @if ($enableMassActions)
                    <td>
                        @php
                            $record_id = $record->{$index};
                        @endphp

                        <span class="checkbox">
                            <input type="checkbox" v-model="dataIds" @change="select($event)" value="{{ $record_id }}">

                            <label class="checkbox-view" for="checkbox"></label>
                        </span>
                    </td>
                @endif

                @foreach ($columns as $column)
                    @php
                        $columnIndex = explode('.', $column['index']);

                        $columnIndex = end($columnIndex);

                        $supportedClosureKey = ['wrapper', 'closure'];

                        $isClosure = ! empty(array_intersect($supportedClosureKey, array_keys($column)));
                    @endphp

                    @if ($isClosure)
                        {{--
                            Depereciation Notice:
                            The following key i.e. `wrapper` will remove in the later version. Use only `closure`
                            key to manipulate the column. This will only hit the raw html.
                        --}}
                        @if (
                            isset($column['wrapper'])
                            && gettype($column['wrapper']) === 'object'
                            && $column['wrapper'] instanceof \Closure
                        )
                            @if (
                                isset($column['closure'])
                                && $column['closure'] == true
                            )
                                <td data-value="{{ $column['label'] }}">{!! $column['wrapper']($record) !!}</td>
                            @else
                                <td data-value="{{ $column['label'] }}">{{ $column['wrapper']($record) }}</td>
                            @endif
                        @elseif (
                            isset($column['closure'])
                            && gettype($column['closure']) === 'object'
                            && $column['closure'] instanceof \Closure
                        )
                            <td data-value="{{ $column['label'] }}">{!! $column['closure']($record) !!}</td>
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
                    <td class="actions" style="white-space: nowrap; width: 100px;" data-value="{{ __('ui::app.datagrid.actions') }}">
                        <div class="action">
                            @foreach ($actions as $action)
                                @php
                                    $toDisplay = (isset($action['condition']) && gettype($action['condition']) == 'object') ? $action['condition']($record) : true;
                                @endphp

                                @if ($toDisplay)
                                    <a
                                        id="{{ $record->{$action['index'] ?? $index} }}"

                                        @if ($action['method'] == 'GET')
                                            href="{{ route($action['route'], $record->{$action['index'] ?? $index}) }}"
                                        @endif

                                        @if ($action['method'] != 'GET')
                                            @if (isset($action['function']))
                                                onclick="{{ $action['function'] }}"
                                            @else
                                                v-on:click="doAction($event)"
                                            @endif
                                        @endif

                                        data-method="{{ $action['method'] }}"
                                        data-action="{{ route($action['route'], $record->{$index}) }}"
                                        data-token="{{ csrf_token() }}"

                                        @if (isset($action['target']))
                                            target="{{ $action['target'] }}"
                                        @endif

                                        @if (isset($action['title']))
                                            title="{{ $action['title'] }}"
                                        @endif
                                    >
                                        <span class="{{ $action['icon'] }}"></span>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </td>
                @endif
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="10">
                <p style="text-align: center;">{{ $norecords }}</p>
            </td>
        </tr>
    @endif
</tbody>
