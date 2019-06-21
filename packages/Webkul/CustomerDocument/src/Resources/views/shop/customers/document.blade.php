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

        <div class="account-items-list">
            @if ($documents->count())
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
                            @foreach ($documents as $document)
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
            @else
                <div class="empty">
                    {{ __('customerdocument::app.admin.customers.empty') }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection