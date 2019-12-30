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
            action="{{ route('velocity.admin.store.meta-data', ['id' => $metaData->id]) }}"
            method="POST"
            @submit.prevent="onSubmit">

            @csrf

            <div class="control-group">
                <label for="footer_content">
                    {{ __('velocity::app.admin.meta-data.activate-slider') }}
                </label>

                <label class="switch">
                    <input
                        type="checkbox"
                        class="control"
                        id="slider"
                        name="slider"
                        data-vv-as="&quot;slides&quot;"
                        {{ $metaData->slider ? 'checked' : ''}}
                        value="{{ $metaData->slider }}" />
                    <span class="slider round"></span>
                </label>
            </div>

            <div class="control-group">
                <label for="footer_content">
                    {{ __('velocity::app.admin.meta-data.home-page-content') }}
                </label>

                <textarea
                    class="control"
                    id="home_page_content"
                    name="home_page_content">
                    {{ $metaData->home_page_content}}
                </textarea>
            </div>

            <div class="control-group">
                <label for="footer_content">
                    {{ __('velocity::app.admin.meta-data.footer-left-content') }}
                </label>

                <textarea
                    class="control"
                    id="footer_left_content"
                    name="footer_left_content">
                    {{ $metaData->footer_left_content}}
                </textarea>
            </div>

            <div class="control-group">
                <label for="footer_content">
                    {{ __('velocity::app.admin.meta-data.footer-middle-content') }}
                </label>

                <textarea
                    class="control"
                    id="footer_middle_content"
                    name="footer_middle_content">
                    {{ $metaData->footer_middle_content}}
                </textarea>
            </div>

            <button class="btn btn-lg btn-primary">
                {{ __('velocity::app.admin.meta-data.update-meta-data') }}
            </button>
        </form>
    </div>
@stop

@push('scripts')
    <script src="{{ asset('vendor/webkul/admin/assets/js/tinyMCE/tinymce.min.js') }}">
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            tinymce.init({
                height: 200,
                width: "100%",
                image_advtab: true,
                valid_elements : '*[*]',
                selector: 'textarea#home_page_content,textarea#footer_left_content,textarea#footer_middle_content',
                plugins: 'image imagetools media wordcount save fullscreen code',
                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat | code',
            });
        });
    </script>
@endpush
