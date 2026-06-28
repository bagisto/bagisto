<x-shop::layouts>
    <x-slot:title>
        {{ trans('marketplace::app.seller.subscriptions.title') }}
    </x-slot>

    <div class="container mx-auto px-4 py-8 max-w-5xl">
        <h1 class="text-2xl font-bold mb-2">{{ trans('marketplace::app.seller.subscriptions.title') }}</h1>
        <p class="text-gray-500 mb-6">{{ trans('marketplace::app.seller.subscriptions.subtitle') }}</p>

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 text-green-700 px-4 py-3">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-50 text-red-700 px-4 py-3">{{ session('error') }}</div>
        @endif
        @if (session('info'))
            <div class="mb-4 rounded-lg bg-blue-50 text-blue-700 px-4 py-3">{{ session('info') }}</div>
        @endif

        @if ($current)
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-5 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-sm text-green-700">{{ trans('marketplace::app.seller.subscriptions.current-plan') }}</span>
                        <div class="text-lg font-bold text-green-800">{{ $current->plan?->name }}</div>
                        <div class="text-sm text-green-700">
                            {{ trans('marketplace::app.seller.subscriptions.renews') }}:
                            {{ $current->current_period_end?->format('d/m/Y') ?? '—' }}
                        </div>
                    </div>
                    <form method="POST" action="{{ route('marketplace.subscriptions.cancel') }}"
                          onsubmit="return confirm('{{ trans('marketplace::app.seller.subscriptions.cancel-confirm') }}')">
                        @csrf
                        <button class="rounded-lg border border-red-300 text-red-600 px-4 py-2 text-sm hover:bg-red-50">
                            {{ trans('marketplace::app.seller.subscriptions.cancel') }}
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <div class="grid gap-6 md:grid-cols-3">
            @foreach ($plans as $plan)
                @php $isCurrent = $current && $current->plan_id === $plan->id; @endphp
                <div class="rounded-2xl border {{ $isCurrent ? 'border-[#635bff] ring-2 ring-[#635bff]/20' : 'border-gray-200' }} p-6 flex flex-col">
                    <div class="text-lg font-bold mb-1">{{ $plan->name }}</div>
                    <div class="text-3xl font-extrabold mb-1">
                        {{ (float) $plan->price <= 0 ? trans('marketplace::app.seller.subscriptions.free') : number_format($plan->price, 2) }}
                        @if ((float) $plan->price > 0)
                            <span class="text-sm font-normal text-gray-500">/{{ $plan->interval }}</span>
                        @endif
                    </div>
                    <ul class="text-sm text-gray-600 space-y-2 my-4 flex-1">
                        <li>• {{ $plan->commission_rate }}% {{ trans('marketplace::app.seller.subscriptions.commission') }}</li>
                        <li>• {{ $plan->max_products ?? trans('marketplace::app.seller.subscriptions.unlimited') }} {{ trans('marketplace::app.seller.subscriptions.products') }}</li>
                        @if ($plan->featured_listing)
                            <li>• {{ trans('marketplace::app.seller.subscriptions.featured') }}</li>
                        @endif
                        @if ($plan->analytics_access)
                            <li>• {{ trans('marketplace::app.seller.subscriptions.analytics') }}</li>
                        @endif
                    </ul>

                    @if ($isCurrent)
                        <button disabled class="rounded-lg bg-gray-100 text-gray-400 px-4 py-2.5 font-semibold cursor-not-allowed">
                            {{ trans('marketplace::app.seller.subscriptions.current') }}
                        </button>
                    @else
                        <form method="POST" action="{{ route('marketplace.subscriptions.subscribe', $plan->id) }}">
                            @csrf
                            <button class="w-full rounded-lg bg-[#635bff] text-white px-4 py-2.5 font-semibold hover:opacity-90">
                                {{ (float) $plan->price <= 0
                                    ? trans('marketplace::app.seller.subscriptions.choose-free')
                                    : trans('marketplace::app.seller.subscriptions.subscribe') }}
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>

        @unless ($configured)
            <p class="mt-6 text-sm text-amber-700">{{ trans('marketplace::app.seller.subscriptions.stripe-note') }}</p>
        @endunless
    </div>
</x-shop::layouts>
