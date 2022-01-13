@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.exchange_rates.add-title') }}
@stop

@section('content')
    <div class="content">

        <form method="POST" action="{{ route('admin.exchange_rates.store') }}" @submit.prevent="onSubmit">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.exchange_rates.index') }}'"></i>

                        {{ __('admin::app.settings.exchange_rates.add-title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.settings.exchange_rates.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()

                    <div class="table">
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>
                                            {{ __('admin::app.settings.exchange_rates.source_currency') }}
                                        </th>
                                        <th>
                                            {{ __('admin::app.settings.exchange_rates.target_currency') }}
                                        </th>
                                        <th>
                                            {{ __('admin::app.settings.exchange_rates.rate') }}
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        {!! view_render_event('bagisto.admin.settings.exchangerate.create.before') !!}

                                        <td>
                                            {{ core()->getBaseCurrencyCode() }}
                                        </td>

                                        <td>
                                            <div class="control-group" :class="[errors.has('target_currency') ? 'has-error' : '']">
                                                <select v-validate="'required'" class="control" name="target_currency" data-vv-as="&quot;{{ __('admin::app.settings.exchange_rates.target_currency') }}&quot;">
                                                    @foreach ($currencies as $currency)
                                                        @if (is_null($currency->exchange_rate))
                                                            <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <span class="control-error" v-if="errors.has('target_currency')">@{{ errors.first('target_currency') }}</span>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="control-group" :class="[errors.has('rate') ? 'has-error' : '']">
                                                <input v-validate="'required'" class="control" id="rate" name="rate" data-vv-as="&quot;{{ __('admin::app.settings.exchange_rates.rate') }}&quot;" value="{{ old('rate') }}"/>
                                                <span class="control-error" v-if="errors.has('rate')">@{{ errors.first('rate') }}</span>
                                            </div>
                                        </td>

                                        {!! view_render_event('bagisto.admin.settings.exchangerate.create.after') !!}
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop