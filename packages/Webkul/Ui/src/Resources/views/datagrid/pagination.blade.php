@if ($results instanceof \Illuminate\Pagination\LengthAwarePaginator)
    <div class="pagination">
        {{ $results->links() }}
    </div>
@endif