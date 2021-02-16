@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.cms.pages.edit-title') }}
@stop

@section('content')
    <div class="content">
        <?php $locale = request()->get('locale') ?: app()->getLocale(); ?>

        <form method="POST" id="page-form" action="" @submit.prevent="onSubmit">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.cms.index') }}'"></i>

                        {{ __('admin::app.cms.pages.edit-title') }}
                    </h1>

                    <div class="control-group">
                        <select class="control" id="locale-switcher" onChange="window.location.href = this.value">
                            @foreach (core()->getAllLocales() as $localeModel)

                                <option value="{{ route('admin.cms.edit', $page->id) . '?locale=' . $localeModel->code }}" {{ ($localeModel->code) == $locale ? 'selected' : '' }}>
                                    {{ $localeModel->name }}
                                </option>

                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="page-action">
                    @if ($page->translate($locale))
                        <a href="{{ route('shop.cms.page', $page->translate($locale)['url_key']) }}" class="btn btn-lg btn-primary" target="_blank">
                            {{ __('admin::app.cms.pages.preview') }}
                        </a>
                    @endif

                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.cms.pages.edit-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">

                <div class="form-container">
                    @csrf()
                    <accordian :title="'{{ __('admin::app.cms.pages.general') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('{{$locale}}[page_title]') ? 'has-error' : '']">
                                <label for="page_title" class="required">{{ __('admin::app.cms.pages.page-title') }}</label>

                                <input type="text" class="control" name="{{$locale}}[page_title]" v-validate="'required'" value="{{ old($locale)['page_title'] ?? ($page->translate($locale)['page_title'] ?? '') }}" data-vv-as="&quot;{{ __('admin::app.cms.pages.page-title') }}&quot;">

                                <span class="control-error" v-if="errors.has('{{$locale}}[page_title]')">@{{ errors.first('{!!$locale!!}[page_title]') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('channels[]') ? 'has-error' : '']">
                                <label for="url-key" class="required">{{ __('admin::app.cms.pages.channel') }}</label>

                                <?php $selectedOptionIds = old('inventory_sources') ?: $page->channels->pluck('id')->toArray() ?>

                                <select type="text" class="control" name="channels[]" v-validate="'required'" value="{{ old('channel[]') }}" data-vv-as="&quot;{{ __('admin::app.cms.pages.channel') }}&quot;" multiple="multiple">
                                    @foreach(app('Webkul\Core\Repositories\ChannelRepository')->all() as $channel)
                                        <option value="{{ $channel->id }}" {{ in_array($channel->id, $selectedOptionIds) ? 'selected' : '' }}>
                                            {{ core()->getChannelName($channel) }}
                                        </option>
                                    @endforeach
                                </select>

                                <span class="control-error" v-if="errors.has('channels[]')">@{{ errors.first('channels[]') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('{{$locale}}[html_content]') ? 'has-error' : '']">
                                <label for="html_content" class="required">{{ __('admin::app.cms.pages.content') }}</label>

                                <textarea type="text" class="control" id="content" name="{{$locale}}[html_content]" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.cms.pages.content') }}&quot;">{{ old($locale)['html_content'] ?? ($page->translate($locale)['html_content'] ?? '') }}</textarea>

                                <span class="control-error" v-if="errors.has('{{$locale}}[html_content]')">@{{ errors.first('{!!$locale!!}[html_content]') }}</span>
                            </div>
                        </div>
                    </accordian>

                    <accordian :title="'{{ __('admin::app.cms.pages.seo') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group">
                                <label for="meta_title">{{ __('admin::app.cms.pages.meta_title') }}</label>

                                <input type="text" class="control" name="{{$locale}}[meta_title]" value="{{ old($locale)['meta_title'] ?? ($page->translate($locale)['meta_title'] ?? '') }}">
                            </div>

                            <div class="control-group" :class="[errors.has('{{$locale}}[url_key]') ? 'has-error' : '']">
                                <label for="url-key" class="required">{{ __('admin::app.cms.pages.url-key') }}</label>

                                <input type="text" class="control" name="{{$locale}}[url_key]" v-validate="'required'" value="{{ old($locale)['url_key'] ?? ($page->translate($locale)['url_key'] ?? '') }}" data-vv-as="&quot;{{ __('admin::app.cms.pages.url-key') }}&quot;">

                                <span class="control-error" v-if="errors.has('{{$locale}}[url_key]')">@{{ errors.first('{!!$locale!!}[url_key]') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="meta_keywords">{{ __('admin::app.cms.pages.meta_keywords') }}</label>

                                <textarea type="text" class="control" name="{{$locale}}[meta_keywords]">{{ old($locale)['meta_keywords'] ?? ($page->translate($locale)['meta_keywords'] ?? '') }}</textarea>

                            </div>

                            <div class="control-group">
                                <label for="meta_description">{{ __('admin::app.cms.pages.meta_description') }}</label>

                                <textarea type="text" class="control" name="{{$locale}}[meta_description]">{{ old($locale)['meta_description'] ?? ($page->translate($locale)['meta_description'] ?? '') }}</textarea>

                            </div>
                        </div>
                    </accordian>
                </div>
            </div>
        </form>
    </div>
@stop

@push('scripts')
    <script src="{{ asset('vendor/webkul/admin/assets/js/tinyMCE/tinymce.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            tinymce.init({
                selector: 'textarea#content',
                height: 200,
                width: "100%",
                plugins: 'image imagetools media wordcount save fullscreen code table lists link hr',
                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor alignleft aligncenter alignright alignjustify | link hr | numlist bullist outdent indent  | removeformat | code | table',
                image_advtab: true,
                valid_elements : '*[*]'
            });
        });
    </script>
@endpush