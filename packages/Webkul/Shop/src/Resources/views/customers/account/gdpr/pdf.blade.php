<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html
    lang="{{ app()->getLocale() }}"
    dir="{{ core()->getCurrentLocale()->direction }}"
>
    <head>
        <meta http-equiv="Cache-control" content="no-cache">

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        
        <title>
            @lang('shop::app.customers.account.gdpr.pdf.title')
        </title>

        @php
            $fontFamily = [
                'regular' => 'Arial, sans-serif',
                'bold'    => 'Arial, sans-serif',
            ];
        @endphp

        <style>
             * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: {{ $fontFamily['regular'] }};
            }

            body {
                font-size: 10px;
                color: #091341;
            }

            b, th {
                font-family: {{ $fontFamily['bold'] }};
            }

            .page-content {
                padding: 12px;
            }

            /* Wrapper Styles */
            .wrapper { 
                padding: 20px; 
                background-color: #f9f9f9; 
                margin: 20px auto; 
                max-width: 1200px; 
                box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
                border-radius: 8px;
            }

            .page-header {
                border-bottom: 1px solid #E9EFFC;
                text-align: center;
                font-size: 24px;
                text-transform: uppercase;
                color: #000DBB;
                padding: 24px 0;
            }

            /* Info Title Styles */
            .info h2 {
                font-weight: 600;
                font-size: 24px;
                color: #555;
                margin-bottom: 10px;
            }

            /* General Table Styling */
            table {
                width: 100%;
                border-spacing: 1px 0;
                border-collapse: separate;
                margin-bottom: 16px;
            }

            table thead th {
                background-color: #E9EFFC;
                color: #000DBB;
                padding: 6px 18px;
                text-align: left;
            }

            table tbody td {
                padding: 9px 18px;
                border-bottom: 1px solid #E9EFFC;
                text-align: left;
                vertical-align: top;
            }
            table tr:nth-child(even) {
                background-color: #f3f3f3;
            }

            /* Order Items Styling */
            .order-items {
                font-size: 16px;
                display: inline-block; 
                border-right: 1px solid #ccc; 
                vertical-align: top; 
            }

            /* Address Styling */
            .address-info {
                margin-top: 20px;
            }
            .address-info h3 {
                font-size: 20px;
                margin-top: 20px;
                font-weight: 600;
            }

            /* Button Styles */
            .button {
                display: inline-block;
                padding: 10px 20px;
                font-size: 16px;
                color: #fff;
                background-color: #007bff;
                border-radius: 5px;
                text-decoration: none;
                text-align: center;
                margin-top: 20px;
            }

            .button:hover {
                background-color: #0056b3;
            }

            /* Mobile Responsive */
            @media (max-width: 768px) {
                .wrapper {
                    padding: 15px;
                    margin: 10px;
                }
                table, table td, table th {
                    font-size: 14px;
                    padding: 8px;
                }
                .header h1 {
                    font-size: 24px;
                }
                .info h2 {
                    font-size: 20px;
                }
            }
        </style>
    </head>
    
    <body>
        <div class="wrapper">
            <div class="page-header">
                <h1>
                    @lang('shop::app.customers.account.gdpr.pdf.title')
                </h1>
            </div>

            <div class="page-content">
                <div class="info">
                    <div class="title">
                        <h2>
                            @lang('shop::app.customers.account.gdpr.pdf.account-info.title')
                        </h2>
                    </div>

                    <div class="content">
                        <table>
                            <tbody>
                                <tr>
                                    <td>@lang('shop::app.customers.account.gdpr.pdf.account-info.first-name')</td>

                                    <td>{{ $param['customerInformation']->first_name ?? 'NA' }}</td>
                                </tr>

                                <tr>
                                    <td>@lang('shop::app.customers.account.gdpr.pdf.account-info.last-name')</td>

                                    <td>{{ $param['customerInformation']->last_name ?? 'NA' }}</td>
                                </tr>

                                <tr>
                                    <td>@lang('shop::app.customers.account.gdpr.pdf.account-info.email')</td>

                                    <td>{{ $param['customerInformation']->email ?? 'NA' }}</td>
                                </tr>

                                <tr>
                                    <td>@lang('shop::app.customers.account.gdpr.pdf.account-info.gender')</td>

                                    <td>{{ $param['customerInformation']->gender ?? 'NA' }}</td>
                                </tr>

                                <tr>
                                    <td>@lang('shop::app.customers.account.gdpr.pdf.account-info.dob')</td>

                                    <td>{{ $param['customerInformation']->date_of_birth ?? 'NA' }}</td>
                                </tr>

                                <tr>
                                    <td>@lang('shop::app.customers.account.gdpr.pdf.account-info.phone')</td>

                                    <td>{{ $param['customerInformation']->phone ?? 'NA' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="wrapper address-info">
            <div class="body">
                <div class="info">
                    <div class="title">
                        <h2>
                            @lang('shop::app.customers.account.gdpr.pdf.address-info.title')
                        </h2>
                    </div>

                    <div class="content">
                        @if(isset($param['address']))
                            @php $count = 1; @endphp

                            @foreach($param['address'] as $params)
                                <h3>
                                    @lang('shop::app.customers.account.gdpr.pdf.address-info.address') #{{$count}}
                                </h3>

                                <table>
                                    <tbody>
                                        <tr>
                                            <td>@lang('shop::app.customers.account.gdpr.pdf.address-info.city')</td>

                                            <td>{{ $params['city'] ?? 'NA' }}</td>
                                        </tr>

                                        <tr>
                                            <td>@lang('shop::app.customers.account.gdpr.pdf.address-info.company')</td>

                                            <td>{{ $params['company_name'] ?? 'NA' }}</td>
                                        </tr>

                                        <tr>
                                            <td>@lang('shop::app.customers.account.gdpr.pdf.address-info.country')</td>

                                            <td>{{ $params['country'] ?? 'NA' }}</td>
                                        </tr>

                                        <tr>
                                            <td>@lang(key: 'shop::app.customers.account.gdpr.pdf.address-info.first-name')</td>

                                            <td>{{ $params['first_name'] ?? 'NA' }}</td>
                                        </tr>

                                        <tr>
                                            <td>@lang('shop::app.customers.account.gdpr.pdf.address-info.last-name')</td>

                                            <td>{{ $params['last_name'] ?? 'N/A' }}</td>
                                        </tr>

                                        <tr>
                                            <td>@lang('shop::app.customers.account.gdpr.pdf.address-info.postcode')</td>

                                            <td>{{ $params['postcode'] ?? 'N/A' }}</td>
                                        </tr>

                                        <tr>
                                            <td>@lang('shop::app.customers.account.gdpr.pdf.address-info.address1')</td>

                                            <td>{{ $params['address1'] ?? 'N/A' }}</td>
                                        </tr>

                                        <tr>
                                            <td>@lang('shop::app.customers.account.gdpr.pdf.address-info.address2')</td>

                                            <td>{{ $params['address2'] ?? 'N/A' }}</td>
                                        </tr>

                                        <tr>
                                            <td>@lang('shop::app.customers.account.gdpr.pdf.address-info.phone')</td>

                                            <td>{{ $params['phone'] ?? 'N/A' }}</td>
                                        </tr>

                                        <tr>
                                            <td>@lang('shop::app.customers.account.gdpr.pdf.address-info.vat-id')</td>

                                            <td>{{ $params['vat_id'] ?? 'N/A' }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                                @php $count++; @endphp

                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
   
        <div class="wrapper">
            <div class="body">
                <div class="info">
                    <div class="title">
                        <h2>
                            @lang('shop::app.customers.account.gdpr.pdf.order-info.title')
                        </h2>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                @foreach(['order-id', 'status', 'product-name', 'sku', 'qty', 'type', 'shipping', 'amount'] as $field)
                                    <th>@lang('shop::app.customers.account.gdpr.pdf.order-info.' . $field)</th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @isset($param['order'])
                                @foreach($param['order'] as $params)
                                    <tr>
                                        @foreach(['id', 'status', 'name', 'sku', 'qty_ordered', 'type', 'shipping_title', 'grand_total'] as $field)
                                            <td>{{ $params[$field] ?? 'N/A' }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
