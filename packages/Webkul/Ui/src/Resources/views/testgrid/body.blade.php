<tbody>
    @foreach($records as $key => $record)

        <tr>
            <td>
                <span class="checkbox">
                    <input type="checkbox" class="indexers" name="checkbox[]">
                    <label class="checkbox-view" for="checkbox1"></label>
                </span>
            </td>
            @foreach($record as $key => $column)
                <td>{{ $column }}</td>
            @endforeach
        </tr>
    @endforeach
</tbody>