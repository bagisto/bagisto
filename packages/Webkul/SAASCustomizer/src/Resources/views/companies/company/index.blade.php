@extends('saas::companies.layouts.master')

@section('page_title')
    Company Insights
@stop

@section('content-wrapper')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>Company Insights</h1>
            </div>
        </div>

        <div class="page-content">
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <td>No. of Products</td>
                            <td>No. of Attributes</td>
                            <td>No. of Customers</td>
                            <td>No. of Customer Groups</td>
                            <td>No. of Categories</td>
                            <td>Domain</td>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>{{ $company[1]['products'] }}</td>
                            <td>{{ $company[1]['attributes'] }}</td>
                            <td>{{ $company[1]['customers'] }}</td>
                            <td>{{ $company[1]['customer-groups'] }}</td>
                            <td>{{ $company[1]['categories'] }}</td>
                            <td>{{ $company[0]->domain }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop