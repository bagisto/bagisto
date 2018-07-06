<div class="{{ $css->datagrid }}">
    <div class="{{ $css->filter }}">
        Filtering Will be here
    </div>

    <table class="{{ $css->table }}">
        <thead class="{{ $css->thead }}">
            <tr>
                @foreach ($columns as $column)
                    <td class="{{ $css->thead_td }}">{!! $column->sorting() !!}</td>
                @endforeach
            </tr>
        </thead>
        <tbody class="{{ $css->tbody }}">
            <tr>
                @foreach ($results as $result)
                    @foreach ($columns as $column)
                        <td class="{{ $css->tbody_td }}">{!! $column->render($result) !!}</td>
                    @endforeach
                @endforeach
            </tr>
        </tbody>
    </table>

    <div class="{{ $css->pagination }}">
        Pagination Will be here
    </div>
</div>