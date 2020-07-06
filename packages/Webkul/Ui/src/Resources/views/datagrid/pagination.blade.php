@if (gettype($results) == 'object')
    <div class="pagination">
        {{ $results->links() }}
    </div>
@endif