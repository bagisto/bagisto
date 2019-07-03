@extends('admin::layouts.content')

@section('page_title')
    {{ __('customerdocument::app.admin.documents.edit-document') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.documents.update', $document->id) }}" @submit.prevent="onSubmit" enctype="multipart/form-data">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('customerdocument::app.admin.documents.edit-document') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('customerdocument::app.admin.documents.save-document') }}
                    </button>
                </div>
            </div>

            <div class="page-content">

                <div class="form-container">
                    @csrf()

                    <input name="_method" type="hidden" value="PUT">

                    <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                        <label for="name" class="required">{{ __('customerdocument::app.admin.customers.name') }}</label>
                        <input v-validate="'required|alpha_spaces'" type="text" class="control" id="name" name="name" value="{{ $document->name }}" data-vv-as="&quot;{{ __('customerdocument::app.admin.customers.name') }}&quot;"/>
                        <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('type') ? 'has-error' : '']">
                        <label for="type" class="required">{{ __('customerdocument::app.admin.documents.type') }}</label>
                        <select name="type" class="control" v-validate="'required'" value="{{ $document->type }}" data-vv-as="&quot;{{ __('customerdocument::app.admin.documents.type') }}&quot;">
                            <option value="product" {{ $document->type == "product" ? 'selected' : '' }}>{{ __('customerdocument::app.admin.documents.product') }}</option>
                            <option value="marketing" {{ $document->type == "marketing" ? 'selected' : '' }}>{{ __('customerdocument::app.admin.documents.marketing') }}</option>
                        </select>
                        <span class="control-error" v-if="errors.has('type')">@{{ errors.first('type') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('status') ? 'has-error' : '']">
                        <label for="status" class="required">{{ __('customerdocument::app.admin.documents.status') }}</label>
                        <select name="status" class="control" value="{{ $document->status }}"  v-validate="'required'" data-vv-as="&quot;{{ __('customerdocument::app.admin.documents.status') }}&quot;">
                            <option value="1" {{ $document->status == "1" ? 'selected' : '' }}>{{ __('customerdocument::app.admin.documents.active') }}</option>
                            <option value="0" {{ $document->status == "0" ? 'selected' : '' }}>{{ __('customerdocument::app.admin.documents.in-active') }}</option>
                        </select>
                        <span class="control-error" v-if="errors.has('status')">@{{ errors.first('status') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('description') ? 'has-error' : '']">
                        <label for="description">{{ __('customerdocument::app.admin.customers.description') }}</label>

                        <textarea class="control" id="description" name="description" data-vv-as="&quot;{{ __('customerdocument::app.admin.customers.description') }}&quot;"
                        >{{ $document->description }}</textarea>

                        <span class="control-error" v-if="errors.has('description')">@{{ errors.first('description') }}</span>
                    </div>

                    <div class="control-group" :class="[errors.has('file') ? 'has-error' : '']">
                        <label for="file" class="required">{{ __
                        ('customerdocument::app.admin.customers.file') }}</label>

                        <a href="{{ route('admin.documents.download', $document->id) }}">
                            <i class="icon sort-down-icon download"></i>
                        </a>

                        <input type="file" class="control" id="file" name="file" data-vv-as="&quot;{{ __('customerdocument::app.admin.customers.file') }}&quot;" value="{{ old('file') }}" style="padding-top: 5px">

                        @php
                            $allowedTypes = core()->getConfigData('customer.settings.documents.allowed_extensions');
                        @endphp

                        <div>
                            <span>{{ __('customerdocument::app.admin.customers.allowed-types-one') }}</span>
                            <span>
                                <b>
                                    @if ($allowedTypes != null)
                                        {{ $allowedTypes }}
                                    @else
                                        {{ __('customerdocument::app.admin.customers.any-type') }}
                                    @endif

                                </b>
                            </span>
                        </div>

                        <span class="control-error" v-if="errors.has('file')">@{{ errors.first('file') }}</span>
                    </div>

                </div>
            </div>
        </form>
    </div>

@stop
