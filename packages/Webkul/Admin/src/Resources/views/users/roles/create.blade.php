@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.users.roles.add-role-title') }}
@stop

@section('content')
    <div class="content">

        <form method="POST" action="{{ route('admin.roles.store') }}" @submit.prevent="onSubmit">
            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('admin::app.users.roles.add-role-title') }}</h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.users.roles.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()

                    <accordian :title="'{{ __('admin::app.users.roles.general') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('admin::app.users.roles.name') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="email" name="name"/>
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="description">{{ __('admin::app.users.roles.description') }}</label>
                                <textarea class="control" id="description" name="description"></textarea>
                            </div>
                        </div>
                    </accordian>

                    <accordian :title="'{{ __('admin::app.users.roles.access-control') }}'" :active="true">
                        <div slot="body">
                            <div class="control-group">
                                <label for="permission_type">{{ __('admin::app.users.roles.permissions') }}</label>
                                <select class="control" name="permission_type" id="permission_type">
                                    <option value="custom">{{ __('admin::app.users.roles.custom') }}</option>
                                    <option value="all">{{ __('admin::app.users.roles.all') }}</option>
                                </select>
                            </div>

                            <div class="control-group">
                                <tree-view value-field="key" id-field="key" items='@json($acl->items)'></tree-view>
                            </div>
                        </div>
                    </accordian>
                </div>
            </div>
        </form>
    </div>
@stop

@section('javascript')
    <script>
        $(document).ready(function () {
            $('#permission_type').on('change', function(e) {
                if($(e.target).val() == 'custom') {
                    $('.tree-container').removeClass('hide')
                } else {
                    $('.tree-container').addClass('hide')
                }
                
            })
        });
    </script>
@stop