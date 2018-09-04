@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.configuration.taxrate.title') }}
@stop

@section('content')
    <div class="content">
        {{-- {{ dd($data[0][0]) }} --}}
        <form method="POST" action="{{ route('admin.taxrule.update', $data[0][0]['id']) }}" @submit.prevent="onSubmit">
            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('admin::app.configuration.taxrate.edit.title') }}</h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.configuration.taxrate.edit.edit-button-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()
                    @method('PUT')
                    <accordian :title="'{{ __('admin::app.configuration.taxrule.general') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('channel') ? 'has-error' : '']">
                                <label for="channel" class="required">{{ __('admin::app.configuration.taxrule.select-channel') }}</label>

                                <select class="control" name="channel">
                                    @foreach(core()->getAllChannels() as $channelModel)

                                        <option @if($data[0][0]['channel_id'] == $channelModel->id) selected @endif value="{{ $channelModel->id }}">
                                            {{ $channelModel->name }}
                                        </option>

                                    @endforeach
                                </select>

                                <span class="control-error" v-if="errors.has('channel')">@{{ errors.first('channel') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('code') ? 'has-error' : '']">
                                <label for="code" class="required">{{ __('admin::app.configuration.taxrule.code') }}</label>

                                <input v-validate="'required'" class="control" id="code" name="code" value="{{ $data[0][0]['code'] }}"/>

                                <span class="control-error" v-if="errors.has('code')">@{{ errors.first('code') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('admin::app.configuration.taxrule.name') }}</label>

                                <input v-validate="'required'" class="control" id="name" name="name" value="{{ $data[0][0]['name'] }}"/>

                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('description') ? 'has-error' : '']">
                                <label for="description" class="required">{{ __('admin::app.configuration.taxrule.description') }}</label>

                                <textarea v-validate="'required'" class="control" id="description" name="description">{{ $data[0][0]['description'] }}</textarea>

                                <span class="control-error" v-if="errors.has('description')">@{{ errors.first('description') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('taxrates') ? 'has-error' : '']">
                                <label for="taxrates" class="required">{{ __('admin::app.configuration.taxrule.select-taxrates') }}</label>

                                @inject('taxRates', 'Webkul\Core\Repositories\TaxRatesRepository')

                                <select multiple="multiple" class="control" id="taxrates" name="taxrates[]" v-validate="'required'">
                                    @foreach($taxRates->all() as $taxRate)

                                        <option value="{{ $taxRate->id }}"
                                        @foreach($data[1] as $selectedRate)
                                            @if($taxRate->id == $selectedRate['id'])
                                                selected
                                            @endif
                                        @endforeach>
                                            {{ $taxRate->identifier }}
                                        </option>

                                    @endforeach
                                </select>

                                <span class="control-error" v-if="errors.has('taxrates')">@{{ errors.first('taxrates[]') }}</span>
                            </div>

                        </div>
                    </accordian>

                </div>
            </div>
        </form>
    </div>
@stop