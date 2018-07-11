@extends('admin::layouts.content')

@section('content')
    <div class="content">

        <form method="POST" action="{{ route('admin.roles.update', $role->id) }}" @submit.prevent="onSubmit">
            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('Edit Role') }}</h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('Save Role') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()

                    <input name="_method" type="hidden" value="PUT">

                    <accordian :title="'{{ __('General') }}'" :active="true">
                        <div class="accordian-content">
                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name">{{ __('Name') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="email" name="name" value="{{ $role->name }}"/>
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="description">{{ __('Description') }}</label>
                                <textarea class="control" id="description" name="description">{{ $role->description }}</textarea>
                            </div>
                        </div>
                    </accordian>

                    <accordian :title="'{{ __('Access Control') }}'" :active="true">
                        <div class="accordian-content">
                            <div class="control-group">
                                <label for="permission_type">{{ __('Permissions') }}</label>
                                <select class="control" name="permission_type" id="permission_type">
                                    <option value="custom" {{ $role->permission_type == 'custom' ? 'selected' : '' }}>{{ __('Custom') }}</option>
                                    <option value="all" {{ $role->permission_type == 'all' ? 'selected' : '' }}>{{ __('All') }}</option>
                                </select>
                            </div>
                            
                            <div class="control-group tree-wrapper {{ $role->permission_type == 'all' ? 'hide' : '' }}">
                                <tree-view value-field="key" id-field="key" items='@json($acl->items)' value='@json($role->permissions)'></tree-view>
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
                    $('.tree-wrapper').removeClass('hide')
                } else {
                    $('.tree-wrapper').addClass('hide')
                }
                
            })
        });
    </script>
@stop