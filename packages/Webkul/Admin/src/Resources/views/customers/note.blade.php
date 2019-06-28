@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.customers.note.title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.customer.note.store', $customer->id) }}">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('admin::app.customers.note.title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.customers.note.save-note') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()

                    <input name="_method" type="hidden" value="PUT">

                    <input name="_customer" type="hidden" value="{{ $customer->id }}">

                    <div class="control-group" :class="[errors.has('channel_id') ? 'has-error' : '']">
                        <label for="notes">{{ __('admin::app.customers.note.enter-note') }} for {{ $customer->name }}</label>

                        <textarea class="control" name="notes" v-validate="'required'">{{ $customer->notes }}</textarea>

                        <span class="control-error" v-if="errors.has('notes')">@{{ errors.first('notes') }}</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop