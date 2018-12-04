<table>
    <thead>
        <tr>
            @foreach ($columns as $column)
                <th>{{ $column->label }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($results as $result)
        <tr>
            @foreach ($columns as $column)
                <td class="">{!! $column->render($result) !!}</td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>