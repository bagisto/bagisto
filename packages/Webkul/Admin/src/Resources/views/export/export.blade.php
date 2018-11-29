<table>
    <thead>
        <tr>
            @foreach($header as $col)
                <th> {{ $col}} </th>
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