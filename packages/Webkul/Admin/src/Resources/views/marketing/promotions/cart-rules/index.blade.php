@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.promotions.cart-rules.title') }}
@stop

@section('content')
    <div class="content">
        <?php $customer_group = request()->get('customer_group') ?: null; ?>
        <?php $channel = request()->get('channel') ?: null; ?>
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.promotions.cart-rules.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.cart-rules.create') }}" class="btn btn-lg btn-primary">
                    {{ __('admin::app.promotions.cart-rules.add-title') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            @inject('cartRuleGrid','Webkul\Admin\DataGrids\CartRuleDataGrid')
            {!! $cartRuleGrid->render() !!}
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function reloadPage(getVar, getVal) {
            let url = new URL(window.location.href);
            url.searchParams.set(getVar, getVal);

            window.location.href = url.href;
        }

    </script>
@endpush
