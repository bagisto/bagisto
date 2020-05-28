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

                <div class="control-group">
                    <select class="control" id="channel-switcher" name="channel"
                            onchange="reloadPage('channel', this.value)">
                        <option value="all" {{ ! isset($channel) ? 'selected' : '' }}>
                            {{ __('admin::app.admin.system.all-channels') }}
                        </option>

                        @foreach (core()->getAllChannels() as $channelModel)
                            <option
                                value="{{ $channelModel->id }}" {{ (isset($channel) && ($channelModel->id) == $channel) ? 'selected' : '' }}>
                                {{ $channelModel->name }}
                            </option>

                        @endforeach
                    </select>
                </div>

                <div class="control-group">
                    <select class="control" id="customer-group-switcher" name="customer_group"
                            onchange="reloadPage('customer_group', this.value)">
                        <option value="all" {{ ! isset($locale) ? 'selected' : '' }}>
                            {{ __('admin::app.admin.system.all-customer-groups') }}
                        </option>

                        @foreach (core()->getAllCustomerGroups() as $customerGroupModel)
                            <option
                                value="{{ $customerGroupModel->id }}" {{ (isset($customerGroupModel) && ($customerGroupModel->id) == $customer_group) ? 'selected' : '' }}>
                                {{ $customerGroupModel->name }}
                            </option>

                        @endforeach
                    </select>
                </div>
            </div>

            <div class="page-action">
                <div class="control-group">
                    <select class="control" id="channel-switcher" name="channel" onchange="reloadPage('channel', this.value)" >
                        <option value="all" {{ ! isset($channel) ? 'selected' : '' }}>
                            {{ __('admin::app.admin.system.all-channels') }}
                        </option>

                        @foreach (core()->getAllChannels() as $channelModel)
                            <option
                                value="{{ $channelModel->id }}" {{ (isset($channel) && ($channelModel->id) == $channel) ? 'selected' : '' }}>
                                {{ $channelModel->name }}
                            </option>

                        @endforeach
                    </select>
                </div>

                <div class="control-group">
                    <select class="control" id="customer-group-switcher" name="customer_group" onchange="reloadPage('customer_group', this.value)" >
                        <option value="all" {{ ! isset($locale) ? 'selected' : '' }}>
                            {{ __('admin::app.admin.system.all-customer-groups') }}
                        </option>

                        @foreach (core()->getAllCustomerGroups() as $customerGroupModel)
                            <option
                                value="{{ $customerGroupModel->id }}" {{ (isset($customerGroupModel) && ($customerGroupModel->id) == $customer_group) ? 'selected' : '' }}>
                                {{ $customerGroupModel->name }}
                            </option>

                        @endforeach
                    </select>
                </div>

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
