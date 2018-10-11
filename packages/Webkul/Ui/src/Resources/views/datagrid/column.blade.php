{{-- <div class="{{ $css->datagrid }}">
    <table class="{{ $css->table }}">
        <thread class="{{ $css->thread }}">
            <tr>
                @foreach ($columns as $column)
                <td class="{{ $css->thead_td }}">{{ $column->lable }}</td>
                @endforeach
            </tr>
        </thread>
        <tbody class="{{ $css->tbody }}">
            <tr>
                @foreach ($results as $result)
                <td class="{{ $css->tbody_td }}">{{ $result->{$columns[$loop->index]->name} }}</td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div> --}}
