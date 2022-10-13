@extends('admin::layouts.master')

@section('page_title')
    {{ __('admin::app.account.title') }}
@stop

@section('content-wrapper')
    <div class="content full-page">
        <form
            method="POST"
            action=""
            enctype="multipart/form-data"
            @submit.prevent="onSubmit"
        >
            <div class="page-header">
                <div class="page-title">
                    <h1>
                        {{ __('admin::app.account.title') }}
                    </h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.save') }}
                    </button>
                </div>
            </div>

            <div class="page-content">

                <div class="form-container">
                    @csrf()

                    <input name="_method" type="hidden" value="PUT">

                    <accordian title="{{ __('admin::app.account.general') }}" :active="true">
                        <div slot="body">
                            <upload-profile-image></upload-profile-image>

                            <div
                                class="control-group"
                                :class="[errors.has('name') ? 'has-error' : '']"
                            >
                                <label for="name" class="required">
                                    {{ __('admin::app.account.name') }}
                                </label>
                                
                                <input
                                    type="text"
                                    name="name"
                                    class="control"
                                    id="name"
                                    value="{{ old('name') ?: $user->name }}"
                                    v-validate="'required'"
                                    data-vv-as="&quot;{{ __('admin::app.account.name') }}&quot;"
                                />

                                <span class="control-error" v-if="errors.has('name')">
                                    @{{ errors.first('name') }}
                                </span>
                            </div>

                            <div class="control-group" :class="[errors.has('email') ? 'has-error' : '']">
                                <label for="email" class="required">
                                    {{ __('admin::app.account.email') }}
                                </label>

                                <input
                                    type="text"
                                    name="email"
                                    class="control"
                                    id="email"
                                    value="{{ old('email') ?: $user->email }}"
                                    v-validate="'required|email'"
                                    data-vv-as="&quot;{{ __('admin::app.account.email') }}&quot;"
                                />

                                <span class="control-error" v-if="errors.has('email')">
                                    @{{ errors.first('email') }}
                                </span>
                            </div>
                        </div>
                    </accordian>

                    <accordian title="{{ __('admin::app.account.change-password') }}" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('password') ? 'has-error' : '']">
                                <label for="password">
                                    {{ __('admin::app.account.password') }}
                                </label>

                                <input
                                    type="password"
                                    name="password"
                                    class="control"
                                    id="password"
                                    ref="password"
                                    v-validate="'min:6'"
                                    data-vv-as="&quot;{{ __('admin::app.account.password') }}&quot;"
                                />

                                <span class="control-error" v-if="errors.has('password')">
                                    @{{ errors.first('password') }}
                                </span>
                            </div>

                            <div class="control-group" :class="[errors.has('password_confirmation') ? 'has-error' : '']">
                                <label for="password_confirmation">
                                    {{ __('admin::app.account.confirm-password') }}
                                </label>

                                <input
                                    type="password"
                                    name="password_confirmation"
                                    class="control"
                                    id="password_confirmation"
                                    v-validate="'min:6|confirmed:password'"
                                    data-vv-as="&quot;{{ __('admin::app.account.confirm-password') }}&quot;"
                                />

                                <span class="control-error" v-if="errors.has('password_confirmation')">
                                    @{{ errors.first('password_confirmation') }}
                                </span>
                            </div>
                        </div>
                    </accordian>

                    <accordian title="{{ __('admin::app.account.current-password') }}" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('current_password') ? 'has-error' : '']">
                                <label for="current_password" class="required">
                                    {{ __('admin::app.account.current-password') }}
                                </label>

                                <input
                                    type="password"
                                    name="current_password"
                                    class="control"
                                    id="current_password"
                                    v-validate="'required|min:6'"
                                    data-vv-as="&quot;{{ __('admin::app.account.current-password') }}&quot;"
                                />

                                <span class="control-error" v-if="errors.has('current_password')">
                                    @{{ errors.first('current_password') }}
                                </span>
                            </div>
                        </div>
                    </accordian>
                </div>
            </div>
        </form>
    </div>
@stop

@push('scripts')
    <script type="text/x-template" id="upload-profile-image-template">
        <div class="form-group" style="margin-bottom: 40px;">
            <div class="image-upload-brick">
                <input
                    type="file"
                    name="image"
                    id="upload-profile"
                    ref="imageInput"
                    @change="addImageView($event)"
                />

                <i class="icon upload-icon"></i>

                <img class="preview" :src="imageData" v-if="imageData.length > 0">
            </div>

            <div class="image-info-brick">
                <span class="field-info">
                    {{ __('admin::app.account.upload-image-info') }}
                </span>
            </div>

            @if ($user->image_url)
                <div style="margin-top: 10px;">
                    <input type="checkbox" name="remove_image"/>

                    <label for="remove" class="">
                        {{ __('admin::app.account.remove-image') }}
                    </label>
                </div>      
            @endif
        </div>
    </script>

    <script>
        Vue.component('upload-profile-image', {
            template: '#upload-profile-image-template',

            data: function() {
                return {
                    imageData: "{{ $user->image_url }}",
                }
            },

            methods: {
                addImageView () {
                    var imageInput = this.$refs.imageInput;

                    if (imageInput.files && imageInput.files[0]) {
                        if (imageInput.files[0].type.includes('image/')) {
                            var reader = new FileReader();

                            reader.onload = (e) => {
                                this.imageData = e.target.result;
                            }

                            reader.readAsDataURL(imageInput.files[0]);
                        } else {
                            imageInput.value = '';

                            alert('{{ __('admin::app.account.image-upload-message') }}');
                        }
                    }
                }
            }
        });
    </script>
@endpush
