<table>
    <thead>
        <tr>
            @foreach ($columns as $column)
                <th>{{ $column['label'] }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($results as $result)
        <tr>
            @foreach ($result as $value)
                <td class="">{{ $value }}</td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>