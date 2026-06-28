<x-admin::layouts>
    <x-slot:title>
        {{ trans('marketplace::app.admin.subscriptions.index.title') }}
    </x-slot>

    <div class="content full-page">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ trans('marketplace::app.admin.subscriptions.index.title') }}</h1>
            </div>
            <div class="page-action">
                <a href="{{ route('admin.marketplace.subscriptions.create') }}" class="btn btn-primary">
                    {{ trans('marketplace::app.admin.subscriptions.index.add-btn') }}
                </a>
            </div>
        </div>

        <div class="page-content">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Interval</th>
                            <th>Commission</th>
                            <th>Max Products</th>
                            <th>Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plans as $plan)
                            <tr>
                                <td>{{ $plan->id }}</td>
                                <td>{{ $plan->name }}</td>
                                <td>{{ number_format($plan->price, 2) }}</td>
                                <td>{{ ucfirst($plan->interval) }}</td>
                                <td>{{ $plan->commission_rate }}%</td>
                                <td>{{ $plan->max_products ?? 'Unlimited' }}</td>
                                <td>{{ $plan->is_active ? 'Yes' : 'No' }}</td>
                                <td>
                                    <a href="{{ route('admin.marketplace.subscriptions.edit', $plan->id) }}"
                                       class="btn btn-sm btn-secondary">Edit</a>
                                    <form method="POST" action="{{ route('admin.marketplace.subscriptions.destroy', $plan->id) }}" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete this plan?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin::layouts>
