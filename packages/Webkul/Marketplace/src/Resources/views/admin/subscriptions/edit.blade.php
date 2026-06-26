@extends('admin::layouts.master')

@section('page_title')
    {{ trans('marketplace::app.admin.subscriptions.edit.title') }}
@stop

@section('content-wrapper')
    <div class="content full-page">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ trans('marketplace::app.admin.subscriptions.edit.title') }}</h1>
            </div>
        </div>

        <div class="page-content">
            <form method="POST" action="{{ route('admin.marketplace.subscriptions.update', $plan->id) }}">
                @csrf @method('PUT')
                <div class="panel">
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="name">Name <span class="required">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $plan->name) }}" class="control" required>
                        </div>

                        <div class="form-group">
                            <label for="price">Price <span class="required">*</span></label>
                            <input type="number" name="price" id="price" value="{{ old('price', $plan->price) }}" step="0.01" min="0" class="control" required>
                        </div>

                        <div class="form-group">
                            <label for="interval">Billing Interval</label>
                            <select name="interval" id="interval" class="control">
                                <option value="monthly" {{ $plan->interval === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="yearly" {{ $plan->interval === 'yearly' ? 'selected' : '' }}>Yearly</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="commission_rate">Commission Rate (%) <span class="required">*</span></label>
                            <input type="number" name="commission_rate" id="commission_rate" value="{{ old('commission_rate', $plan->commission_rate) }}" step="0.01" min="0" max="100" class="control" required>
                        </div>

                        <div class="form-group">
                            <label for="max_products">Max Products</label>
                            <input type="number" name="max_products" id="max_products" value="{{ old('max_products', $plan->max_products) }}" min="0" class="control">
                        </div>

                        <div class="form-group">
                            <label for="stripe_price_id">Stripe Price ID</label>
                            <input type="text" name="stripe_price_id" id="stripe_price_id" value="{{ old('stripe_price_id', $plan->stripe_price_id) }}" class="control">
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="is_active" value="1" {{ $plan->is_active ? 'checked' : '' }}>
                                Active
                            </label>
                        </div>
                    </div>

                    <div class="panel-footer">
                        <button type="submit" class="btn btn-primary">Update Plan</button>
                        <a href="{{ route('admin.marketplace.subscriptions.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
