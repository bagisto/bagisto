@extends('admin::layouts.content')

@section('page_title')
    {{ __('velocity::app.admin.meta-data.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('velocity::app.admin.meta-data.title') }}</h1>
            </div>
        </div>

        <form
            method="POST"
            @submit.prevent="onSubmit"
            enctype="multipart/form-data"
            action="{{ route('velocity.admin.store.meta-data', ['id' => $metaData->id]) }}">

            @csrf

            <accordian :title="'{{ __('admin::app.catalog.attributes.general') }}'" :active="false">
                <div slot="body">
                    <div class="control-group">
                        <label>{{ __('velocity::app.admin.meta-data.activate-slider') }}</label>

                        <label class="switch">
                            <input
                                type="checkbox"
                                class="control"
                                id="slides"
                                name="slides"
                                data-vv-as="&quot;slides&quot;"
                                {{ $metaData->slider ? 'checked' : ''}} />

                            <span class="slider round"></span>
                        </label>
                    </div>

                    <div class="control-group">
                        <label>{{ __('velocity::app.admin.meta-data.activate-slider') }}</label>

                        <input
                            type="text"
                            class="control"
                            id="sidebar_category_count"
                            name="sidebar_category_count"
                            value="{{ $metaData->sidebar_category_count }}" />
                    </div>

                    <div class="control-group">
                        <label>{{ __('velocity::app.admin.meta-data.home-page-content') }}</label>

                        <textarea
                            class="control"
                            id="home_page_content"
                            name="home_page_content">
                            {{ $metaData->home_page_content}}
                        </textarea>
                    </div>
                </div>
            </accordian>

            <accordian :title="'{{ __('admin::app.catalog.products.images') }}'" :active="false">
                <div slot="body">
                    <div class="control-group">
                        <label>{{ __('velocity::app.admin.meta-data.advertisement-one') }}</label>

                        <image-wrapper
                            :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'"
                            input-name="images[1]">
                            {{-- :images='@json($metaData->advertisement[1])'> --}}
                        </image-wrapper>
                    </div>

                    <div class="control-group">
                        <label>{{ __('velocity::app.admin.meta-data.advertisement-two') }}</label>

                        <image-wrapper
                            :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'"
                            input-name="images[2]">
                        </image-wrapper>
                    </div>

                    <div class="control-group">
                        <label>{{ __('velocity::app.admin.meta-data.advertisement-three') }}</label>

                        <image-wrapper
                            :button-label="'{{ __('admin::app.catalog.products.add-image-btn-title') }}'"
                            input-name="images[3]">
                        </image-wrapper>
                    </div>
                </div>
            </accordian>

            <accordian :title="'{{ __('velocity::app.admin.meta-data.footer') }}'" :active="false">
                <div slot="body">
                    <div class="control-group">
                        <label>{{ __('velocity::app.admin.meta-data.subscription-content') }}</label>

                        <textarea
                            class="control"
                            id="subscription_bar_content"
                            name="subscription_bar_content">
                            {{ $metaData->subscription_bar_content}}
                        </textarea>
                    </div>

                    <div class="control-group">
                        <label>{{ __('velocity::app.admin.meta-data.footer-left-content') }}</label>

                        <textarea
                            class="control"
                            id="footer_left_content"
                            name="footer_left_content">
                            {{ $metaData->footer_left_content}}
                        </textarea>
                    </div>

                    <div class="control-group">
                        <label>{{ __('velocity::app.admin.meta-data.footer-middle-content') }}</label>

                        <textarea
                            class="control"
                            id="footer_middle_content"
                            name="footer_middle_content">
                            {{ $metaData->footer_middle_content}}
                        </textarea>
                    </div>
                </div>
            </accordian>

            <button class="btn btn-lg btn-primary" style="margin-top: 20px">
                {{ __('velocity::app.admin.meta-data.update-meta-data') }}
            </button>
        </form>
    </div>
@stop

@push('scripts')
    <script src="{{ asset('vendor/webkul/admin/assets/js/tinyMCE/tinymce.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            tinymce.init({
                height: 200,
                width: "100%",
                image_advtab: true,
                valid_elements : '*[*]',
                selector: 'textarea#home_page_content,textarea#footer_left_content,textarea#subscription_bar_content,textarea#footer_middle_content',
                plugins: 'image imagetools media wordcount save fullscreen code',
                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat | code',
            });
        });
    </script>
@endpush
