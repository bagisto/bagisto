@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.settings.sliders.edit-title') }}
@stop

@section('content')
    <div class="content">
        <?php $locale = request()->get('locale') ?: app()->getLocale(); ?>

        <form method="POST" action="{{ route('admin.sliders.update', $slider->id) }}" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                        {{ __('admin::app.settings.sliders.edit-title') }}

                        @if ($slider->locale)
                            <span class="locale">[{{ $slider->locale }}]</span>
                        @endif
                        
                    </h1>
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

                    {!! view_render_event('bagisto.admin.settings.slider.edit.before') !!}

                    <div class="control-group" :class="[errors.has('title') ? 'has-error' : '']">
                        <label for="title" class="required">{{ __('admin::app.settings.sliders.name') }}</label>
                        <input type="text" class="control" name="title" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.settings.sliders.name') }}&quot;" value="{{ $slider->title ?: old('title') }}">
                        <span class="control-error" v-if="errors.has('title')">@{{ errors.first('title') }}</span>
                    </div>

                    <?php $channels = core()->getAllChannels() ?>
                    <div class="control-group" :class="[errors.has('channel_id') ? 'has-error' : '']">
                        <label for="channel_id">{{ __('admin::app.settings.sliders.channels') }}</label>
                        <select class="control" id="channel_id" name="channel_id" data-vv-as="&quot;{{ __('admin::app.settings.sliders.channels') }}&quot;" value="" v-validate="'required'">
                            @foreach ($channels as $channel)
                                <option value="{{ $channel->id }}" @if ($channel->id == $slider->channel_id) selected @endif>
                                    {{ __($channel->name) }}
                                </option>
                            @endforeach
                        </select>
                        <span class="control-error" v-if="errors.has('channel_id')">@{{ errors.first('channel_id') }}</span>
                    </div>

                    <div class="control-group {!! $errors->has('image.*') ? 'has-error' : '' !!}">
                        <label class="required">{{ __('admin::app.catalog.categories.image') }}</label>

                        <image-wrapper :button-label="'{{ __('admin::app.settings.sliders.image') }}'" input-name="image" :multiple="false" :images='"{{ Storage::url($slider->path) }}"'></image-wrapper>

                        <span class="control-error" v-if="{!! $errors->has('image.*') !!}">
                            @foreach ($errors->get('image.*') as $key => $message)
                                @php echo str_replace($key, 'Image', $message[0]); @endphp
                            @endforeach
                        </span>
                    </div>

                    <div class="control-group">
                        <label for="content">{{ __('admin::app.settings.sliders.content') }}</label>

                        <div class="panel-body">
                            <textarea id="tiny" class="control" id="add_content" name="content" rows="5">{{ $slider->content ? : old('content') }}</textarea>
                        </div>

                        <span class="control-error" v-if="errors.has('content')">@{{ errors.first('content') }}</span>
                    </div>

                    {!! view_render_event('bagisto.admin.settings.slider.edit.after', ['slider' => $slider]) !!}
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/webkul/admin/assets/js/tinyMCE/tinymce.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            tinymce.init({
                selector: 'textarea#tiny',
                height: 200,
                width: "100%",
                plugins: 'image imagetools media wordcount save fullscreen code',
                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat | code',
                image_advtab: true,
                templates: [
                    { title: 'Test template 1', content: 'Test 1' },
                    { title: 'Test template 2', content: 'Test 2' }
                ],
            });
        });
    </script>
@endpush
