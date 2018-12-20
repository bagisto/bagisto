<div class="table">
    {{-- @foreach($massoperations as $key => $value)
        {{ $value['type'] }}
    @endforeach
    {{ dd($massoperations) }} --}}
    <table class="{{ $css->table }}">
        @include('ui::datagrid.table.head')
        @include('ui::datagrid.table.body')
    </table>
    @include('ui::datagrid.pagination')
</div>