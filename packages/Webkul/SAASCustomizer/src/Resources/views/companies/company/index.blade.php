@extends('saas::companies.layouts.master')

@section('page_title')
    Company Stats
@stop

@section('content-wrapper')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>Company Stats</h1>
            </div>
        </div>

        <div class="page-content">
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <td>No. of Products</td>
                            <td>No. of Customers</td>
                            <td>Domain</td>
                            <td>Owner</td>
                            <td>Location</td>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>{{ $company[1]['products'] }}</td>
                            <td>{{ $company[1]['customers'] }}</td>
                            <td>{{ $company[0]->domain }}</td>
                            <td>{{ $company[1]['products'] }}</td>
                            <td>{{ $company[1]['products'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop