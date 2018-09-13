@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.catalog.products.edit-title') }}
@stop

@section('content')
    <div class="content">
        <?php $locale = request()->get('locale') ?: app()->getLocale(); ?>
        <?php $channel = request()->get('channel') ?: core()->getCurrentChannelCode(); ?>

        <form method="POST" action="" @submit.prevent="onSubmit" enctype="multipart/form-data">

            <div class="page-header">

                <div class="page-title">
                    <h1>{{ __('admin::app.catalog.products.edit-title') }}</h1>

                    <div class="control-group">
                        <select class="control" id="channel-switcher" name="channel">
                            @foreach(core()->getAllChannels() as $channelModel)

                                <option value="{{ $channelModel->code }}" {{ ($channelModel->code) == $channel ? 'selected' : '' }}>
                                    {{ $channelModel->name }}
                                </option>

                            @endforeach
                        </select>
                    </div>

                    <div class="control-group">
                        <select class="control" id="locale-switcher" name="locale">
                            @foreach(core()->getAllLocales() as $localeModel)

                                <option value="{{ $localeModel->code }}" {{ ($localeModel->code) == $locale ? 'selected' : '' }}>
                                    {{ $localeModel->name }}
                                </option>

                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.catalog.products.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                @csrf()

                <input name="_method" type="hidden" value="PUT">

                @foreach ($product->attribute_family->attribute_groups as $attributeGroup)
                    @if(count($attributeGroup->custom_attributes))
                        <accordian :title="'{{ __($attributeGroup->name) }}'" :active="true">
                            <div slot="body">

                                @foreach($attributeGroup->custom_attributes as $attribute)

                                    @if(!$product->super_attributes->contains($attribute))

                                        <?php
                                            $validations = [];
                                            $disabled = false;
                                            if($product->type == 'configurable' && in_array($attribute->code, ['price', 'cost', 'special_price', 'special_price_from', 'special_price_to', 'width', 'height', 'depth', 'weight'])) {
                                                if(!$attribute->is_required)
                                                    continue;

                                                $disabled = true;
                                            } else {
                                                if($attribute->is_required) {
                                                    array_push($validations, 'required');
                                                }

                                                array_push($validations, $attribute->validation);
                                            }

                                            $validations = implode('|', array_filter($validations));
                                        ?>

                                        @if(view()->exists($typeView = 'admin::catalog.products.field-types.' . $attribute->type))

                                            <div class="control-group" :class="[errors.has('{{ $attribute->code }}') ? 'has-error' : '']">
                                                <label for="{{ $attribute->code }}" {{ $attribute->is_required ? 'class=required' : '' }}>
                                                    {{ $attribute->admin_name }}

                                                    <?php
                                                        $channel_locale = [];
                                                        if($attribute->value_per_channel) {
                                                            array_push($channel_locale, $channel);
                                                        }

                                                        if($attribute->value_per_locale) {
                                                            array_push($channel_locale, $locale);
                                                        }
                                                    ?>

                                                    @if(count($channel_locale))
                                                        <span class="locale">[{{ implode(' - ', $channel_locale) }}]</span>
                                                    @endif
                                                </label>

                                                @include ($typeView)

                                                <span class="control-error" v-if="errors.has('{{ $attribute->code }}')">@{{ errors.first('{!! $attribute->code !!}') }}</span>
                                            </div>

                                        @endif

                                    @endif

                                @endforeach

                            </div>
                        </accordian>
                    @endif
                @endforeach

                @if ($form_accordians)

                    @foreach ($form_accordians->items as $accordian)

                        @include ($accordian['view'])

                    @endforeach

                @endif

            </div>

        </form>
    </div>
@stop

@push('scripts')
    <script>

        $(document).ready(function () {
            $('#channel-switcher, #locale-switcher').on('change', function (e) {
                $('#channel-switcher').val()
                var query = '?channel=' + $('#channel-switcher').val() + '&locale=' + $('#locale-switcher').val();

                window.location.href = "{{ route('admin.catalog.products.edit', $product->id)  }}" + query;
            })
        });
    </script>
@endpush