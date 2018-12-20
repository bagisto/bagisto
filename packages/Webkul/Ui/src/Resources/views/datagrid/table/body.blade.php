<tbody class="{{ $css->tbody }}">
    @if(count($results) == 0)
    <tr>
        <td colspan="{{ count($columns)+1 }}" style="text-align: center;">
            {{ __('ui::app.datagrid.no-records') }}
        </td>
    </tr>
    @endif
    @foreach ($results as $result)
    <tr>
        @if(count($massoperations))
        <td>
            <span class="checkbox">
                <input type="checkbox" class="indexers" id="{{ $result->id }}" name="checkbox[]">
                <label class="checkbox-view" for="checkbox1"></label>
            </span>
        </td>
        @endif
        @foreach ($columns as $column)
            @if(isset($column->closure))
                @if($column->closure == true)
                    <td>{!! $column->render($result) !!}</td>
                @endif
            @else
                <td>{{ $column->render($result) }}</td>
            @endif
        @endforeach
        @if(count($actions))
            <td class="action">
                @foreach($actions as $action)
                    <a
                        href="{{ route($action['route'], $result->id) }}"
                        class="Action-{{ $action['type'] }}"
                        id="{{ $result->id }}"
                        @if(isset($action['confirm_text']))
                            onclick="return confirm_click('{{ $action['confirm_text'] }}');"
                        @endif
                        >
                        <i
                        @if(isset($action['icon-alt']))
                            title="{{ $action['icon-alt'] }}"
                        @endif
                        class="{{ $action['icon'] }}"></i>
                    </a>
                @endforeach
            </td>
        @endif
    </tr>
    @endforeach
</tbody>