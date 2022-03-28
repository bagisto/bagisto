@extends('admin::layouts.master')

@section('page_title')
    {{ __('admin::app.sales.transactions.view-title', ['transaction_id' => $transaction->id]) }}
@stop

@section('content-wrapper')
    <div class="content full-page">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.sales.transactions.index') }}'"></i>

                    {{ __('admin::app.sales.transactions.view-title', ['transaction_id' => $transaction->id]) }}
                </h1>
            </div>

            <div class="page-action">
            </div>
        </div>

        <div class="page-content">
            <div class="sale-container">

                <accordian title="{{ __('admin::app.sales.transactions.transaction-data') }}" :active="true">
                    <div slot="body">
                        <div class="sale">
                            <div class="sale-section" style="width:100%">
                                <div class="secton-title">
                                    <span>{{ __('admin::app.sales.transactions.transaction-data') }}</span>
                                </div>

                                <div class="section-content">
                                    <div class="row">
                                        <span class="title">
                                            {{ __('admin::app.sales.transactions.transaction-id') }}
                                        </span>

                                        <span class="value">
                                            {{ $transaction->transaction_id }}
                                        </span>
                                    </div>

                                    <div class="row">
                                        <span class="title">
                                            {{ __('admin::app.sales.transactions.order-id') }}
                                        </span>

                                        <span class="value">
                                            <a href="{{ route('admin.sales.orders.view', $transaction->order_id)}}">
                                                {{ $transaction->order_id }}
                                            </a>
                                        </span>
                                    </div>

                                    @if($transaction->invoice_id)
                                    <div class="row">
                                        <span class="title">
                                            {{ __('admin::app.sales.transactions.invoice-id') }}
                                        </span>

                                        <span class="value">
                                            <a href="{{ route('admin.sales.invoices.view', $transaction->invoice_id)}}">
                                                {{ $transaction->invoice_id }}
                                            </a>
                                        </span>
                                    </div>
                                    @endif

                                    <div class="row">
                                        <span class="title">
                                            {{ __('admin::app.sales.transactions.payment-method') }}
                                        </span>

                                        <span class="value">
                                            {{ core()->getConfigData('sales.paymentmethods.' . $transaction->payment_method . '.title') }}
                                        </span>
                                    </div>

                                    <div class="row">
                                        <span class="title">
                                            {{ __('admin::app.sales.transactions.status') }}
                                        </span>

                                        <span class="value">
                                            {{ $transaction->status }}
                                        </span>
                                    </div>

                                    <div class="row">
                                        <span class="title">
                                            {{ __('admin::app.sales.transactions.created-at') }}
                                        </span>

                                        <span class="value">
                                            {{ $transaction->created_at }}
                                        </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                        
                    </div>
                </accordian>

                <accordian title="{{ __('admin::app.sales.transactions.transaction-details') }}" :active="true">
                    <div slot="body">
                        @php
                            $transData = json_decode(json_encode(json_decode($transaction['data'])), true);
                        @endphp

                        <div class="sale">
                            <div class="sale-section" style="width: 100%;">
                                <div class="secton-title">
                                    <span>{{ __('admin::app.sales.transactions.transaction-details') }}</span>
                                </div>

                                <div class="section-content">
                                    @foreach ($transactionDeatilsData as $key => $data)
                                        <div class="row">
                                            <span class="title">
                                                {{ $key }}
                                            </span>

                                            <span class="value">
                                                {{ $data }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                        
                    </div>
                </accordian>

            </div>
        </div>
    </div>
@stop