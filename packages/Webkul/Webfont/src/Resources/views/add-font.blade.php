@extends('admin::layouts.content')

@section('page_title')
    {{ __('webfont::app.title') }}
@stop

@section('content')
    <div class="content">
        <web-font-form></web-font-form>
    </div>
@stop

@push('scripts')
    <script type="text/x-template" id="add-font-form">
        <div>
            <form method="POST" action="{{ route('admin.cms.webfont.store') }}" @submit.prevent="onSubmit">

                <div class="page-header">
                    <div class="page-title">
                        <h1>
                            <i class="icon angle-left-icon back-link" onclick="history.length > 1 ? history.go(-1) : window.location = '{{ url('/admin/dashboard') }}';"></i>

                            {{ __('webfont::app.title') }}
                        </h1>
                    </div>

                    <div class="page-action fixed-action">
                        <button type="submit" class="btn btn-lg btn-primary">
                            {{ __('webfont::app.save-btn-title') }}
                        </button>
                    </div>
                </div>

                <div class="page-content">

                    <div class="form-container">
                        <input type="hidden" name="fonts" v-model="fonts">

                        <div class="control-group" :class="[errors.has('first_name') ? 'has-error' : '']">
                            <label for="selected_fonts" class="required">{{ __('webfont::app.select-font-label') }}</label>

                            <br/>

                            <multiselect v-model="selected_fonts" :close-on-select="false" :options="font_list" :searchable="true" :show-labels="true" :custom-label="customLabel" placeholder="{{ __('webfont::app.select-fonts') }}" track-by="family" :multiple="true"></multiselect>

                            <span class="control-error" v-if="errors.has('selected_fonts')">@{{ errors.first('selected_fonts') }}</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </script>

    <script>
        Vue.component('web-font-form', {
            template: '#add-font-form',

            data() {
                return {
                    font_list: @json($fonts),
                    fonts: '',
                    selected_fonts: []
                }
            },

            mounted() {
            },

            methods: {
                customLabel(option) {
                    return option.family + ' (' + option.category + ')';
                },

                onSubmit(e) {
                    temp_fonts = [];

                    for (i in this.selected_fonts) {
                        temp_fonts.push(this.selected_fonts[i].family + ', ' + this.selected_fonts[i].category);
                    }

                    if (this.selected_fonts.length == 0) {
                        alert('{{ __('webfont::app.select-values') }}');
                    } else {
                        this.fonts = JSON.stringify(temp_fonts);

                        this.$validator.validateAll().then(result => {
                            if (result) {
                                e.target.submit();
                            }
                        });
                    }
                },

                addFlashMessages() {
                    const flashes = this.$refs.flashes;

                    flashMessages.forEach(function(flash) {
                        flashes.addFlash(flash);
                    }, this);
                }
            }
        });
    </script>
@endpush