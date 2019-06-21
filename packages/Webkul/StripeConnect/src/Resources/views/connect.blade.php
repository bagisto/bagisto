@extends('admin::layouts.content')

@section('page_title')
    Connect Stripe Account
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>Connect Your Stripe Account</h1>
            </div>
            <div class="page-action">
            </div>
        </div>

        <div class="page-content">
            @if (core()->getConfigData('stripe.connect.details.clientid') != null)
                @inject('stripeConnect', 'Webkul\StripeConnect\Repositories\StripeConnectRepository')

                @if($stripeConnect->findWhere(['company_id' => \Company::getCurrent()->id])->count() == 0)
                    <a href="https://connect.stripe.com/oauth/authorize?client_id={{ core()->getConfigData('stripe.connect.details.clientid') }}&response_type=code&stripe_landing=register&scope=read_write&stripe_user[redirect_uri]={{ route('admin.stripe.retrieve-grant') }}" class="btn btn-lg btn-primary">Connect Stripe</a>
                @else
                    <a href="{{ route('admin.stripe.revoke-access') }}" class="btn btn-lg btn-primary">Revoke Your Stripe Account's Access</a>
                @endif
            @else
                <span class="warning" style="font-size: 18px; font-weight: bold; color: #556cd6">
                    Please provide your stripe client ID to connect your account with the platform.
                    <br/><br/>
                    <a href="{{ route('admin.configuration.index', 'stripe') }}">Click here</a>
                </span>
            @endif
        </div>
    </div>
@stop