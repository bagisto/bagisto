@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.categories.edit-title') }}
@stop

@push('css')
    <style>
       @media only screen and (max-width: 728px){
            .content-container .content .page-header .page-title{
                width: 100%;
            }

            .content-container .content .page-header .page-title .control-group {
                margin-top: 20px!important;
                width: 100%!important;
                margin-left: 0!important;
            }

            .content-container .content .page-header .page-action {
                margin-top: 10px!important;
                float: left;
            }
       }
    </style>
@endpush

@section('content')
    <div class="content">
        
        @php
            $locale = core()->getRequestedLocaleCode();
        @endphp

        <form method="POST" action="" @submit.prevent="onSubmit" enctype="multipart/form-data">
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link" onclick="window.location = '{{ route('admin.catalog.categories.index') }}'"></i>

                        {{ __('admin::app.catalog.categories.edit-title') }}
                    </h1>

                    <div class="control-group">
                        <select class="control" id="locale-switcher" onChange="window.location.href = this.value">
                            @foreach (core()->getAllLocales() as $localeModel)

                                <option value="{{ route('admin.catalog.categories.update', $category->id) . '?locale=' . $localeModel->code }}" {{ ($localeModel->code) == $locale ? 'selected' : '' }}>
                                    {{ $localeModel->name }}
                                </option>

                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.catalog.categories.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <tabs>
                    {!! view_render_event('bagisto.admin.catalog.category.edit_form.before', ['category' => $category]) !!}
                    
                    <tab name="{{ __('admin::app.catalog.categories.edit-title') }}" :selected="true">
                        @include('admin::catalog.categories.partials.edit-category')
                    </tab>
                    
                    {!! view_render_event('bagisto.admin.catalog.category.edit_form.after', ['category' => $category]) !!}
                    
                    {!! view_render_event('bagisto.admin.catalog.category.products.before', ['category' => $category]) !!}

                    <tab name="{{ __('admin::app.catalog.categories.products') }}">
                        @include('admin::catalog.categories.partials.products')
                    </tab>

                    {!! view_render_event('bagisto.admin.catalog.category.products.after', ['category' => $category]) !!}
                </tabs>
            </div>
        </form>
    </div>
@stop

@push('scripts')
    @include('admin::layouts.tinymce')

    <script type="text/x-template" id="description-template">
        <div class="control-group" :class="[errors.has('{{$locale}}[description]') ? 'has-error' : '']">
            <label for="description" :class="isRequired ? 'required' : ''">{{ __('admin::app.catalog.categories.description') }}
                <span class="locale">[{{ $locale }}]</span>
            </label>
            <textarea v-validate="isRequired ? 'required' : ''" class="control" id="description" name="{{$locale}}[description]" data-vv-as="&quot;{{ __('admin::app.catalog.categories.description') }}&quot;">{{ old($locale)['description'] ?? ($category->translate($locale)['description'] ?? '') }}</textarea>
            <span class="control-error" v-if="errors.has('{{$locale}}[description]')">@{{ errors.first('{!!$locale!!}[description]') }}</span>
        </div>
    </script>

    <script>
        Vue.component('description', {
            template: '#description-template',

            inject: ['$validator'],

            data: function() {
                return {
                    isRequired: true,
                }
            },

            created: function () {
                let self = this;

                $(document).ready(function () {
                    $('#display_mode').on('change', function (e) {
                        if ($('#display_mode').val() != 'products_only') {
                            self.isRequired = true;
                        } else {
                            self.isRequired = false;
                        }
                    })

                    if ($('#display_mode').val() != 'products_only') {
                        self.isRequired = true;
                    } else {
                        self.isRequired = false;
                    }

                    tinyMCEHelper.initTinyMCE({
                        selector: 'textarea#description',
                        height: 200,
                        width: "100%",
                        plugins: 'image imagetools media wordcount save fullscreen code table lists link hr',
                        toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor link hr | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent  | removeformat | code | table',
                    });
                });
            }
        });
        
        function showEditQuantityForm(productId) {
            $(`#product-${productId}-quantity`).hide();
            $(`#edit-product-${productId}-quantity-form-block`).show();
        }
        
        function cancelEditQuantityForm(productId) {
            $(`#edit-product-${productId}-quantity-form-block`).hide();
            $(`#product-${productId}-quantity`).show();
        }
        
        function saveEditQuantityForm(updateSource, productId) {
            let quantityFormData = $(`#edit-product-${productId}-quantity-form`).serialize();
            axios
                .post(updateSource, quantityFormData)
                .then(function (response) {
                    let data = response.data;
                    $(`#inventoryErrors${productId}`).text('');
                    $(`#edit-product-${productId}-quantity-form-block`).hide();
                    $(`#product-${productId}-quantity-anchor`).text(data.updatedTotal);
                    $(`#product-${productId}-quantity`).show();
                })
                .catch(function ({ response }) {
                    let { data } = response;
                    $(`#inventoryErrors${productId}`).text(data.message);
                });
        }
    </script>
@endpush
