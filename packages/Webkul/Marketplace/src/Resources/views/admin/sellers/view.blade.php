<x-admin::layouts>
    <x-slot:title>
        {{ $seller->shop_name }}
    </x-slot>

    <div class="content full-page">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ $seller->shop_name }}</h1>
            </div>
            <div class="page-action">
                @if (!$seller->isApproved())
                    <form method="POST" action="{{ route('admin.marketplace.sellers.approve', $seller->id) }}" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-success">Approve</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.marketplace.sellers.suspend', $seller->id) }}" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">Suspend</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="page-content">
            <div class="form-container">
                <table class="table">
                    <tr><th>Status</th><td><span class="badge badge-{{ $seller->status->color() }}">{{ $seller->status->label() }}</span></td></tr>
                    <tr><th>Customer</th><td>{{ $seller->customer?->name }} ({{ $seller->customer?->email }})</td></tr>
                    <tr><th>Shop URL</th><td><a href="{{ route('marketplace.store', $seller->shop_url) }}" target="_blank" style="color:#2563eb;text-decoration:underline">/loja/{{ $seller->shop_url }}</a></td></tr>
                    <tr><th>Phone</th><td>{{ $seller->phone ?? '—' }}</td></tr>
                    <tr><th>Commission Rate</th><td>{{ $seller->commission_rate }}%</td></tr>
                    <tr><th>Subscription</th><td>{{ $seller->subscription?->plan?->name ?? 'None' }}</td></tr>
                    <tr><th>Registered</th><td>{{ $seller->created_at->format('d/m/Y H:i') }}</td></tr>
                </table>

                @if ($seller->description)
                    <p>{{ $seller->description }}</p>
                @endif
            </div>

            {{-- ============ MONEY ROUTING (per-seller settlement) ============ --}}
            <div class="form-container" style="margin-top:24px">
                <h2 style="font-weight:600;margin-bottom:12px">Roteamento de pagamento</h2>

                <table class="table">
                    <tr>
                        <th>Modo atual</th>
                        <td>
                            @if (($seller->payout_mode?->value ?? 'platform') === 'stripe')
                                <span class="badge badge-info">Stripe Connect (split automático para o vendedor)</span>
                            @else
                                <span class="badge badge-success">Plataforma coleta (você paga o vendedor manualmente)</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Conta Stripe</th>
                        <td>
                            {{ $seller->stripe_account_id ?: '—' }}
                            @if ($seller->stripe_payouts_enabled)
                                <span class="badge badge-success">repasses ✓</span>
                            @endif
                        </td>
                    </tr>
                </table>

                <form method="POST" action="{{ route('admin.marketplace.sellers.payout-mode', $seller->id) }}">
                    @csrf
                    <div class="form-group">
                        <label for="payout_mode">Escolha como este vendedor recebe</label>
                        <select name="payout_mode" id="payout_mode" class="control">
                            <option value="platform" {{ ($seller->payout_mode?->value ?? 'platform') === 'platform' ? 'selected' : '' }}>
                                Plataforma coleta (você paga o vendedor manualmente)
                            </option>
                            <option value="stripe" {{ ($seller->payout_mode?->value ?? '') === 'stripe' ? 'selected' : '' }}>
                                Stripe Connect (split automático para o vendedor)
                            </option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar roteamento</button>
                </form>
            </div>
        </div>
    </div>
</x-admin::layouts>
