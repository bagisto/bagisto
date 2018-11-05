<tbody class="{{ $css->tbody }}">
    @if(count($results) == 0)
    <tr>
        <td colspan="{{ count($columns)+1 }}" style="text-align: center;">
            No Records Found.
        </td>
    </tr>
    @endif
    @foreach ($results as $result)
    <tr>

        <td class="">
            <span class="checkbox">
                <input type="checkbox" class="indexers" id="{{ $result->id }}" name="checkbox[]">
                <label class="checkbox-view" for="checkbox1"></label>
            </span>
        </td>
        @foreach ($columns as $column)
            <td class="">{!! $column->render($result) !!}</td>
        @endforeach
        @if(count($actions))
        <td class="action">
            @foreach($actions as $action)
                <a @if($action['type'] == "Edit") href="{{ url()->current().'/edit/'.$result->id }}" @elseif($action['type'] == "View") href="{{ url()->current().'/view/'.$result->id }}" @elseif($action['type']=="Delete") href="{{ url()->current().'/delete/'.$result->id }}" @endif  class="Action-{{ $action['type'] }}" id="{{ $result->id }}" onclick="return confirm_click('{{ $action['confirm_text'] }}');">
                    <i class="{{ $action['icon'] }}"></i>
                </a>
            @endforeach
        </td>
        @endif
    </tr>
    @endforeach
</tbody>