<?php

$customerDocumentRepository = app('Webkul\CustomerDocument\Repositories\CustomerDocumentRepository');

$documents = $customerDocumentRepository->findWhere(['customer_id' => $customer->id, 'status' => 1]);

?>

@section('css')
    @parent

    <style>
        .modal-container {
            top: 20px !important;
        }
    </style>
@stop

<accordian :title="'{{ __('customerdocument::app.admin.customers.documents') }}'" :active="true">
    <div slot="body">

        <button type="button" style="margin-bottom : 20px" class="btn btn-md btn-primary" @click="showModal('addDocument')">
            {{ __('customerdocument::app.admin.customers.add-document') }}
        </button>

        <div class="table" style="margin-bottom: 20px;">
            <table>
                <thead>
                    <tr>
                        <th>{{ __('customerdocument::app.admin.customers.name') }}</th>
                        <th>{{ __('customerdocument::app.admin.documents.type') }}</th>
                        <th>{{ __('customerdocument::app.admin.customers.description') }}</th>
                        <th>{{ __('customerdocument::app.admin.customers.download') }}</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($documents as $document)
                        <tr>
                            <td>{{ $document->name }}</td>
                            <td>{{ $document->type }}</td>
                            <td>{{ $document->description }}</td>
                            <td>
                                <a href="{{ route('admin.documents.download', $document->id) }}">
                                    <i class="icon sort-down-icon"></i>
                                </a>
                            </td>
                            <td class="actions">
                                <a href="{{ route('admin.customer.documents.delete', $document->id) }}">
                                    <i class="icon trash-icon"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</accordian>

<modal id="addDocument" :is-open="modalIds.addDocument">
    <h3 slot="header">{{ __('customerdocument::app.admin.customers.add-document') }}</h3>

    <div slot="body">
        <form method="POST" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data" @submit.prevent="onSubmit">
            @csrf()

            <input type="hidden" name="customer_id" value="{{ $customer->id }}">

            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                <label for="name" class="required">{{ __('customerdocument::app.admin.customers.name') }}</label>
                <input v-validate="'required'" type="text" class="control" id="name" name="name" data-vv-as="&quot;{{ __('customerdocument::app.admin.customers.name') }}&quot;" value="{{ old('name') }}"/>
                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
            </div>

            <div class="control-group" :class="[errors.has('type') ? 'has-error' : '']">
                <label for="type" class="required">{{ __('customerdocument::app.admin.documents.type') }}</label>
                <select name="type" class="control" v-validate="'required'" data-vv-as="&quot;{{ __('customerdocument::app.admin.documents.type') }}&quot;">
                    <option value="product">{{ __('customerdocument::app.admin.documents.product') }}</option>
                    <option value="marketing">{{ __('customerdocument::app.admin.documents.marketing') }}</option>
                    <option value="other">{{ __('customerdocument::app.admin.documents.other') }}</option>
                </select>
                <span class="control-error" v-if="errors.has('type')">@{{ errors.first('type') }}</span>
            </div>

            <div class="control-group" :class="[errors.has('description') ? 'has-error' : '']">
                <label for="description">{{ __('customerdocument::app.admin.customers.description') }}</label>

                <textarea class="control" id="description" name="description" data-vv-as="&quot;{{ __('customerdocument::app.admin.customers.description') }}&quot;" value="{{ old('description') }}"/
                ></textarea>

                <span class="control-error" v-if="errors.has('description')">@{{ errors.first('description') }}</span>
            </div>

            <div class="control-group" :class="[errors.has('file') ? 'has-error' : '']">
                <label for="file" class="required">{{ __
                ('customerdocument::app.admin.customers.file') }}</label>

                <input v-validate="'required'" type="file" class="control" id="file" name="file" data-vv-as="&quot;{{ __('customerdocument::app.admin.customers.file') }}&quot;" value="{{ old('file') }}" style="padding-top: 5px">

                @php
                    $allowedTypes = core()->getConfigData('customer.settings.documents.allowed_extensions');
                @endphp

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
                <span class="control-error" v-if="errors.has('file')">@{{ errors.first('file') }}</span>
            </div>

            <button type="submit" class="btn btn-lg btn-primary">
                {{ __('customerdocument::app.admin.customers.submit') }}
            </button>

        </form>
    </div>
</modal>