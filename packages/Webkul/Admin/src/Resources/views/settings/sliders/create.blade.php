@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.sliders.add-title') }}
@stop

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.sliders.create') }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('admin::app.settings.sliders.add-title') }}</h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.settings.sliders.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()
                    <accordian :title="'{{ __('admin::app.settings.sliders.general') }}'" :active="true">
                        <div slot="body">

                            <div class="control-group" :class="[errors.has('title') ? 'has-error' : '']">
                                <label for="title">{{ __('admin::app.settings.sliders.title') }}</label>
                                <input type="text" class="control" name="title" v-validate="'required'">
                                <span class="control-error" v-if="errors.has('title')">@{{ errors.first('title') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('channel_id') ? 'has-error' : '']">
                                <label for="channel_id">{{ __('admin::app.settings.sliders.channels') }}</label>
                                <select class="control" id="channel_id" name="channel_id" value="" v-validate="'required'">
                                    @foreach($channels[0] as $channel)
                                        <option value="{{ $channel->id }}">{{ __($channel->name) }}</option>
                                    @endforeach
                                </select>
                                <span class="control-error" v-if="errors.has('channel_id')">@{{ errors.first('channel_id') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('image') ? 'has-error' : '']">
                                <label for="new_image">{{ __('admin::app.settings.sliders.image') }}</label>

                                <image-upload>

                                    <input type="file" class="control" id="add_image" name="image" value="" v-validate="'image|required'" placeholder="Upload from Outer"/>

                                    <span class="control-error" v-if="errors.has('image')">@{{ errors.first('image') }}</span>
                                    {{-- The image field validation is not working, resolve it. --}}

                                </image-upload>
                            </div>

                            <div class="control-group" :class="[errors.has('content') ? 'has-error' : '']">
                                <label for="content">{{ __('admin::app.settings.sliders.content') }}</label>

                                <textarea class="control" id="add_content" name="content" v-validate="'required'" rows="5"></textarea>

                                <span class="control-error" v-if="errors.has('content')">@{{ errors.first('content') }}</span>
                            </div>

                        </div>
                    </accordian>
                </div>
            </div>
        </form>
    </div>
@endsection