@extends('admin::layouts.content')

@section('page_title')
{{ __('bulkupload::app.admin.bulk-upload.bulk-upload-dataflow-profile') }}
@endsection

@section('content')
    <div class="account-content">
        <div class="account-layout">
                <!-- Import New products -->
            <div class="import-new-products mt-45">
                <div class="heading">
                    <h1>Edit Profile</h1>
                    <span>Profile Information</span><br><br>
                </div>

                <form method="POST" action="{{ route('admin.bulk-upload.dataflow.update-profile',$profiles->id) }}">
                        @csrf
                    <?php $familyId = app('request')->input('family') ?>

                    <div class="control-group">
                        <label for="profile_name" class="required">{{ __('admin::app.catalog.categories.name') }}</label>
                        <input type="text" class="control" id="profile_name" name="profile_name" value="{{ $profiles->profile_name}}"/>
                    </div>

                    <div class="control-group" :class="[errors.has('attribute_family_id') ? 'has-error' : '']">
                        <label for="attribute_family_id" class="required">{{ __('admin::app.catalog.products.familiy') }}</label>

                        <select class="control" value="" id="attribute_family_id" name="attribute_family_id" {{ $familyId ? 'disabled' : '' }}>
                            @foreach ($families as $family)
                                <option value="{{ $family->id }}" {{ ($familyId == $family->id || old('attribute_family_id') == $family->id) ? 'selected' : '' }}>{{ $family->name }}</option>
                                @endforeach
                        </select>

                        @if ($familyId)
                            <input type="hidden" name="attribute_family_id" value="{{ $familyId }}"/>
                        @endif
                        <span class="control-error" v-if="errors.has('attribute_family_id')">@{{ errors.first('attribute_family_id') }}</span>
                    </div>

                    <div class="page-action" style="display:flex; justify-content: space-between;">
                        <button type="submit" class="btn btn-lg btn-primary">
                            {{ __('bulkupload::app.shop.bulk-upload.update-profile')  }}
                        </button>
                    </div>
                    <br>
                </form>
            </div>

            <div class="page-content">
                {!! app('Webkul\Bulkupload\DataGrids\Admin\ProfileDataGrid')->render() !!}
            </div>
        </div>
    </div>
@endsection
