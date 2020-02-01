<table>
    <thead>
        <tr>
            @foreach ($columns as $key => $value)
                <?php
                   $title =  $value == 'increment_id' ? 'order_id' : $value;
                ?>
                <th>{{ $title }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($records as $record)
            <tr>
                @foreach($record as $column => $value)
                    <td>{{ $value }} </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>