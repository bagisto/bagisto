@extends('shop::layouts.master')

@section('page_title')
    {{ __('customerdocument::app.admin.customers.documents') }}
@endsection

@section('content-wrapper')

<div class="account-content">

    @include('shop::customers.account.partials.sidemenu')

    <div class="account-layout">

        <div class="account-head mb-15">
            <span class="account-heading">{{ __('customerdocument::app.admin.customers.documents') }}</span>

            <div class="horizontal-rule"></div>
        </div>

        <div class="sale-container">

            <tabs>

                @if (! empty($productDocument))
                    <tab name="{{ __('customerdocument::app.admin.documents.product') }}" :selected="true">

                        <div class="account-items-list" style="display: none;">
                            <div class="table" style="margin-bottom: 20px;">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>{{ __('customerdocument::app.admin.customers.name') }}</th>
                                            <th>{{ __('customerdocument::app.admin.customers.description') }}</th>
                                            <th>{{ __('customerdocument::app.admin.customers.download') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($productDocument as $document)
                                            <tr>
                                                <td>{{ $document->name }}</td>
                                                <td>{{ $document->description }}</td>
                                                <td>
                                                    <a href="{{ route('customer.document.download', $document->id) }}">
                                                        <i class="icon sort-down-icon"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </tab>
                @endif

                @if (! empty($productDocument))
                    <tab name="{{ __('customerdocument::app.admin.documents.marketing') }}">
                        <div class="account-items-list" style="display: none;">
                            <div class="table" style="margin-bottom: 20px;">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>{{ __('customerdocument::app.admin.customers.name') }}</th>
                                            <th>{{ __('customerdocument::app.admin.customers.description') }}</th>
                                            <th>{{ __('customerdocument::app.admin.customers.download') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($marketingDocument as $document)
                                            <tr>
                                                <td>{{ $document->name }}</td>
                                                <td>{{ $document->description }}</td>
                                                <td>
                                                    <a href="{{ route('customer.document.download', $document->id) }}">
                                                        <i class="icon sort-down-icon"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </tab>
                @endif

            </tabs>

            @if (empty($productDocument) && empty($productDocument))
                <div class="empty">
                    {{ __('customerdocument::app.admin.customers.empty') }}
                </div>
            @endif
        </div>
    </div>

</div>

@endsection

@push('scripts')

<script>
    $(document).ready(function() {
        $('.account-items-list').css('display','block');
    });
</script>

@endpush