<table>
    <thead>
        <tr>
            @foreach ($columns as $column)
                <th>{{ $column->getLabel() }}</th>
            @endforeach
        </tr>
    </thead>

    <tbody>
        @foreach ($records as $record)
            <tr>
                @foreach($columns as $column)
                    @if ($closure = $column->getClosure())
                        <td>{!! $closure($record) !!}</td>
                    @else
                        <td>{{ $record->{$column->getIndex()} }}</td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
