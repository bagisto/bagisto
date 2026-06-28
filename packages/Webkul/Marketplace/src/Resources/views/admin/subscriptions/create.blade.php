<x-admin::layouts>
    <x-slot:title>
        {{ trans('marketplace::app.admin.subscriptions.create.title') }}
    </x-slot>

    <div class="content full-page">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ trans('marketplace::app.admin.subscriptions.create.title') }}</h1>
            </div>
        </div>

        <div class="page-content">
            <form method="POST" action="{{ route('admin.marketplace.subscriptions.store') }}">
                @csrf
                <div class="panel">
                    <div class="panel-body">
                        <div class="form-group" class="{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name">Name <span class="required">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="control" required>
                            @if ($errors->has('name'))
                                <span class="control-error">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="slug">Slug <span class="required">*</span></label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}" class="control" required>
                        </div>

                        <div class="form-group">
                            <label for="price">Price <span class="required">*</span></label>
                            <input type="number" name="price" id="price" value="{{ old('price', 0) }}" step="0.01" min="0" class="control" required>
                        </div>

                        <div class="form-group">
                            <label for="interval">Billing Interval <span class="required">*</span></label>
                            <select name="interval" id="interval" class="control">
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="commission_rate">Commission Rate (%) <span class="required">*</span></label>
                            <input type="number" name="commission_rate" id="commission_rate" value="{{ old('commission_rate', 10) }}" step="0.01" min="0" max="100" class="control" required>
                        </div>

                        <div class="form-group">
                            <label for="max_products">Max Products (leave blank for unlimited)</label>
                            <input type="number" name="max_products" id="max_products" value="{{ old('max_products') }}" min="0" class="control">
                        </div>

                        <div class="form-group">
                            <label for="stripe_price_id">Stripe Price ID</label>
                            <input type="text" name="stripe_price_id" id="stripe_price_id" value="{{ old('stripe_price_id') }}" class="control">
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                                Active
                            </label>
                        </div>
                    </div>

                    <div class="panel-footer">
                        <button type="submit" class="btn btn-primary">Create Plan</button>
                        <a href="{{ route('admin.marketplace.subscriptions.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin::layouts>
