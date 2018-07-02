@extends('admin::layouts.content')

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.roles.store') }}" @submit.prevent="onSubmit">
            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('Add Role') }}</h1>
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

                    <accordian :title="'{{ __('General') }}'" :active="true">
                        <div class="accordian-content">
                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name">{{ __('Name') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="email" name="name"/>
                                <span class="control-error" v-if="errors.has('name')">@{{ errors.first('name') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="description">{{ __('Description') }}</label>
                                <textarea class="control" id="description" name="description"></textarea>
                            </div>
                        </div>
                    </accordian>

                    <accordian :title="'{{ __('Access Control') }}'" :active="true">
                        <div class="accordian-content">
                            <div class="control-group">
                                <label for="permission_type">{{ __('Permissions') }}</label>
                                <select class="control" name="permission_type" id="permission_type">
                                    <option value="custom">{{ __('Custom') }}</option>
                                    <option value="all">{{ __('All') }}</option>
                                </select>
                            </div>

                            <div class="control-group">
                                <tree-view/>
                            </div>                            
                        </div>
                    </accordian>
                </div>
            </div>
        </form>
    </div>
@stop