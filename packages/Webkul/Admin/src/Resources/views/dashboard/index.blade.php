@extends('admin::layouts.master')

@section('page_title')
    {{ __('admin::app.dashboard.title') }}
@stop

@section('content-wrapper')

    <div class="content full-page dashboard">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('admin::app.dashboard.title') }}</h1>
            </div>

            <div class="page-action">
                <date-filter></date-filter>
               
            </div>

            <date-mobile-filter></date-mobile-filter>
        </div>

        <div class="page-content">

            <div class="dashboard-stats">

                <div class="dashboard-card">
                    <div class="title">
                        {{ __('admin::app.dashboard.total-customers') }}
                    </div>

                    <div class="data">
                        {{ $statistics['total_customers']['current'] }}

                        <span class="progress">
                            @if ($statistics['total_customers']['progress'] < 0)
                                <span class="icon graph-down-icon"></span>
                                {{ __('admin::app.dashboard.decreased', [
                                        'progress' => -number_format($statistics['total_customers']['progress'], 1)
                                    ])
                                }}
                            @else
                                <span class="icon graph-up-icon"></span>
                                {{ __('admin::app.dashboard.increased', [
                                        'progress' => number_format($statistics['total_customers']['progress'], 1)
                                    ])
                                }}
                            @endif
                        </span>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="title">
                        {{ __('admin::app.dashboard.total-orders') }}
                    </div>

                    <div class="data">
                        {{ $statistics['total_orders']['current'] }}

                        <span class="progress">
                            @if ($statistics['total_orders']['progress'] < 0)
                                <span class="icon graph-down-icon"></span>
                                {{ __('admin::app.dashboard.decreased', [
                                        'progress' => -number_format($statistics['total_orders']['progress'], 1)
                                    ])
                                }}
                            @else
                                <span class="icon graph-up-icon"></span>
                                {{ __('admin::app.dashboard.increased', [
                                        'progress' => number_format($statistics['total_orders']['progress'], 1)
                                    ])
                                }}
                            @endif
                        </span>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="title">
                        {{ __('admin::app.dashboard.total-sale') }}
                    </div>

                    <div class="data">
                        {{ core()->formatBasePrice($statistics['total_sales']['current']) }}

                        <span class="progress">
                            @if ($statistics['total_sales']['progress'] < 0)
                                <span class="icon graph-down-icon"></span>
                                {{ __('admin::app.dashboard.decreased', [
                                        'progress' => -number_format($statistics['total_sales']['progress'], 1)
                                    ])
                                }}
                            @else
                                <span class="icon graph-up-icon"></span>
                                {{ __('admin::app.dashboard.increased', [
                                        'progress' => number_format($statistics['total_sales']['progress'], 1)
                                    ])
                                }}
                            @endif
                        </span>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="title">
                        {{ __('admin::app.dashboard.average-sale') }}
                    </div>

                    <div class="data">
                        {{ core()->formatBasePrice($statistics['avg_sales']['current']) }}

                        <span class="progress">
                            @if ($statistics['avg_sales']['progress'] < 0)
                                <span class="icon graph-down-icon"></span>
                                {{ __('admin::app.dashboard.decreased', [
                                        'progress' => -number_format($statistics['avg_sales']['progress'], 1)
                                    ])
                                }}
                            @else
                                <span class="icon graph-up-icon"></span>
                                {{ __('admin::app.dashboard.increased', [
                                        'progress' => number_format($statistics['avg_sales']['progress'], 1)
                                    ])
                                }}
                            @endif
                        </span>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="title">
                        {{ __('admin::app.dashboard.total-unpaid-invoices') }}
                    </div>

                    <div class="data">
                        {{ core()->formatBasePrice($statistics['total_unpaid_invoices']) }}
                    </div>
                </div>

            </div>

            <div class="graph-stats">

                <div class="left-card-container graph">
                    <div class="card" style="overflow: hidden;">
                        <div class="card-title" style="margin-bottom: 30px;">
                            {{ __('admin::app.dashboard.sales') }}
                        </div>

                        <div class="card-info" style="height: 100%;">

                            <canvas id="myChart" style="width: 100%; height: 87%"></canvas>

                        </div>
                    </div>
                </div>

                <div class="right-card-container category">
                    <div class="card">
                        <div class="card-title">
                            {{ __('admin::app.dashboard.top-performing-categories') }}
                        </div>

                        <div class="card-info {{ !count($statistics['top_selling_categories']) ? 'center' : '' }}">
                            <ul>

                                @foreach ($statistics['top_selling_categories'] as $item)

                                    <li>
                                        <a href="{{ route('admin.catalog.categories.edit', $item->category_id) }}">
                                            <div class="description">
                                                <div class="name">
                                                    {{ $item->name }}
                                                </div>

                                                <div class="info">
                                                    {{ __('admin::app.dashboard.product-count', ['count' => $item->total_products]) }}
                                                    &nbsp;.&nbsp;
                                                    {{ __('admin::app.dashboard.sale-count', ['count' => $item->total_qty_invoiced]) }}
                                                </div>
                                            </div>

                                            <span class="icon angle-right-icon"></span>
                                        </a>
                                    </li>

                                @endforeach

                            </ul>

                            @if (! count($statistics['top_selling_categories']))

                                <div class="no-result-found">

                                    <i class="icon no-result-icon"></i>
                                    <p>{{ __('admin::app.common.no-result-found') }}</p>

                                </div>

                            @endif
                        </div>
                    </div>
                </div>

            </div>

            <div class="sale-stock">
                <div class="card">
                    <div class="card-title">
                        {{ __('admin::app.dashboard.top-selling-products') }}
                    </div>

                    <div class="card-info {{ !count($statistics['top_selling_products']) ? 'center' : '' }}">
                        <ul>

                            @foreach ($statistics['top_selling_products'] as $item)

                                <li>
                                    <a href="{{ route('admin.catalog.products.edit', $item->product_id) }}">
                                        <div class="product image">
                                            <?php $productBaseImage = productimage()->getProductBaseImage($item->product); ?>

                                            <img class="item-image" src="{{ $productBaseImage['small_image_url'] ?? asset('vendor/webkul/ui/assets/images/product/small-product-placeholder.webp') }}" />
                                        </div>

                                        <div class="description do-not-cross-arrow">
                                            <div class="name ellipsis">
                                                @if (isset($item->name))
                                                    {{ $item->name }}
                                                @endif
                                            </div>

                                            <div class="info">
                                                {{ __('admin::app.dashboard.sale-count', ['count' => $item->total_qty_invoiced]) }}
                                            </div>
                                        </div>

                                        <span class="icon angle-right-icon"></span>
                                    </a>
                                </li>

                            @endforeach

                        </ul>

                        @if (! count($statistics['top_selling_products']))

                            <div class="no-result-found">

                                <i class="icon no-result-icon"></i>
                                <p>{{ __('admin::app.common.no-result-found') }}</p>

                            </div>

                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-title">
                        {{ __('admin::app.dashboard.customer-with-most-sales') }}
                    </div>

                    <div class="card-info {{ !count($statistics['customer_with_most_sales']) ? 'center' : '' }}">
                        <ul>

                            @foreach ($statistics['customer_with_most_sales'] as $item)

                                <li>
                                    @if ($item->customer_id)
                                        <a href="{{ route('admin.customer.edit', $item->customer_id) }}">
                                    @endif

                                        <div class="image">
                                            <span class="icon profile-pic-icon"></span>
                                        </div>

                                        <div class="description do-not-cross-arrow">
                                            <div class="name ellipsis">
                                                {{ $item->customer_full_name }}
                                            </div>

                                            <div class="info">
                                                {{ __('admin::app.dashboard.order-count', ['count' => $item->total_orders]) }}
                                                    &nbsp;.&nbsp;
                                                {{ __('admin::app.dashboard.revenue', [
                                                    'total' => core()->formatBasePrice($item->total_base_grand_total)
                                                    ])
                                                }}
                                            </div>
                                        </div>

                                        <span class="icon angle-right-icon"></span>

                                    @if ($item->customer_id)
                                        </a>
                                    @endif
                                </li>

                            @endforeach

                        </ul>

                        @if (! count($statistics['customer_with_most_sales']))

                            <div class="no-result-found">

                                <i class="icon no-result-icon"></i>
                                <p>{{ __('admin::app.common.no-result-found') }}</p>

                            </div>

                        @endif
                    </div>

                </div>

                <div class="card">
                    <div class="card-title">
                        {{ __('admin::app.dashboard.stock-threshold') }}
                    </div>

                    <div class="card-info {{ !count($statistics['stock_threshold']) ? 'center' : '' }}">
                        <ul>

                            @foreach ($statistics['stock_threshold'] as $item)

                                <li>
                                    <a href="{{ route('admin.catalog.products.edit', $item->product_id) }}">
                                        <div class="image">
                                            <?php $productBaseImage = productimage()->getProductBaseImage($item->product); ?>

                                            <img class="item-image" src="{{ $productBaseImage['small_image_url'] }}" />
                                        </div>

                                        <div class="description do-not-cross-arrow">
                                            <div class="name ellipsis">
                                                @if (isset($item->product->name))
                                                    {{ $item->product->name }}
                                                @endif
                                            </div>

                                            <div class="info">
                                                {{ __('admin::app.dashboard.qty-left', ['qty' => $item->total_qty]) }}
                                            </div>
                                        </div>

                                        <span class="icon angle-right-icon"></span>
                                    </a>
                                </li>

                            @endforeach

                        </ul>

                        @if (! count($statistics['stock_threshold']))

                            <div class="no-result-found">

                                <i class="icon no-result-icon"></i>
                                <p>{{ __('admin::app.common.no-result-found') }}</p>

                            </div>

                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

@stop

@push('scripts')

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>

    <script type="text/x-template" id="date-filter-template">
        <div id="calender-destop">
            <div class="control-group date">
                <date @onChange="applyFilter('start', $event)" hide-remove-button="1"><input type="text" class="control" id="start_date" value="{{ $startDate->format('Y-m-d') }}" placeholder="{{ __('admin::app.dashboard.from') }}" v-model="start"/></date>
            </div>

            <div class="control-group date">
                <date @onChange="applyFilter('end', $event)" hide-remove-button="1"><input type="text" class="control" id="end_date" value="{{ $endDate->format('Y-m-d') }}" placeholder="{{ __('admin::app.dashboard.to') }}" v-model="end"/></date>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="date-mobile-filter-template">
        <div>
            <div id="calender-mobile">
                <span  @click="openCalender()"></span>
            </div>
            <div v-if="toggleCalenderIcon">
                <div id="date-start" style="">
                    <div class="control-group start-date" style="margin-top:15px">
                    <label for="type">{{ __('admin::app.dashboard.from') }}</label>
                        <date @onChange="setDate('start', $event)" hide-remove-button="1">
                            <input type="text" class="control" id="start_date" value="{{ $startDate->format('Y-m-d') }}" placeholder="{{ __('admin::app.dashboard.from') }}" v-model="start"/>
                        </date>
                    </div>
                </div>

                <div id="date-end" style="">
                    <div class="control-group end-date" style="margin-top:15px">
                        <label for="type">{{ __('admin::app.dashboard.to') }}</label>
                        <date @onChange="setDate('end', $event)" hide-remove-button="1">
                            <input type="text" class="control" id="end_date" value="{{ $endDate->format('Y-m-d') }}" placeholder="{{ __('admin::app.dashboard.to') }}" v-model="end"/>
                        </date>
                    </div>
                </div>

                <div id="date-submit" style="">
                    <button class="btn btn-lg btn-primary" @click="applyFilter">Submit</button>
                </div>              
            </div>            
        </div>
    </script>

    <script>
        Vue.component('date-filter', {

            template: '#date-filter-template',

            data: function() {
                return {
                    start: "{{ $startDate->format('Y-m-d') }}",
                    end: "{{ $endDate->format('Y-m-d') }}",
                }
            },

            methods: {
                applyFilter: function(field, date) {
                    this[field] = date;

                    window.location.href = "?start=" + this.start + '&end=' + this.end;
                }
            }
        });

        Vue.component('date-mobile-filter', {

            template: '#date-mobile-filter-template',

            data: function() {
                return {
                    start: "{{ $startDate->format('Y-m-d') }}",
                    end: "{{ $endDate->format('Y-m-d') }}",
                    toggleCalenderIcon : 0
                }
            },

            methods: {

                openCalender: function(){

                    if(this.toggleCalenderIcon){
                        this.toggleCalenderIcon = 0;
                        $('#calender-mobile span').css('top','0');
                    }else{
                        this.toggleCalenderIcon = 1;
                        $('#calender-mobile span').css('top','-40px');
                    }
                },

                setDate: function(field, date) {
                    this[field] = date;
                },

                applyFilter: function() {
                    window.location.href = "?start=" + this.start + '&end=' + this.end;
                }
            }
        });

        $(document).ready(function () {

            var ctx = document.getElementById("myChart").getContext('2d');

            var data = @json($statistics['sale_graph']);

            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data['label'],
                    datasets: [{
                        data: data['total'],
                        backgroundColor: 'rgba(34, 201, 93, 1)',
                        borderColor: 'rgba(34, 201, 93, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        display: false
                    },
                    scales: {
                        xAxes: [{
                            maxBarThickness: 20,
                            gridLines : {
                                display : false,
                                drawBorder: false,
                            },
                            ticks: {
                                beginAtZero: true,
                                fontColor: 'rgba(162, 162, 162, 1)'
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                drawBorder: false,
                            },
                            ticks: {
                                padding: 20,
                                beginAtZero: true,
                                fontColor: 'rgba(162, 162, 162, 1)'
                            }
                        }]
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                        displayColors: false,
                        callbacks: {
                            label: function(tooltipItem, dataTemp) {
                                return data['formated_total'][tooltipItem.index];
                            }
                        }
                    }
                }
            });
        });
    </script>

@endpush