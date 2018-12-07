@extends('admin::layouts.content')

@section('page_title')
    {{ __('admin::app.configuration.sales.shipping-method.title') }}
@stop

@section('content')
    <div class="content">
        <?php $locale = request()->get('locale') ?: app()->getLocale(); ?>
        <?php $channel = request()->get('channel') ?: core()->getDefaultChannelCode(); ?>

        <form method="POST" action="" @submit.prevent="onSubmit" enctype="multipart/form-data">

            <div class="page-header">

                <div class="page-title">
                    <h1>
                        {{ __('admin::app.configuration.sales.shipping-method.title') }}
                    </h1>

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
                        {{ __('admin::app.configuration.sales.shipping-method.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()

                    @foreach (config('core.carriers') as $method => $carrier)
                    
                        <accordian :title="'{{ __(config('carriers.' . $method . '.title')) }}'" :active="true">
                            <div slot="body">
                                @foreach ($carrier as $field)

                                    <?php
                                        $validations = [];
                                        $disabled = false;

                                        if (isset($field['validation'])) {
                                            array_push($validations, $field['validation']);
                                        } else {
                                            $disabled = true;
                                        }

                                        $validations = implode('|', array_filter($validations));
                                    ?>

                                    @if (view()->exists($typeView = 'admin::configuration.sales.field-types.' . $field['type']))

                                        <?php
                                            $name = 'carriers' . '.' . $method . '.' . $field['name'];
                                        ?>

                                        <div class="control-group {{ $field['type'] }}" :class="[errors.has('{{ $name }}') ? 'has-error' : '']">
                                            <label for="{{ $name }}" {{ $disabled == false   ? 'class=required' : '' }}>

                                                {{ $field['title'] }}

                                                <?php
                                                    $channel_locale = [];

                                                    if(isset($field['channel_based']) && $field['channel_based'])
                                                    {
                                                        array_push($channel_locale, $channel);
                                                    }

                                                    if(isset($field['locale_based']) && $field['locale_based']) {
                                                        array_push($channel_locale, $locale);
                                                    }
                                                ?>

                                                @if(count($channel_locale))
                                                    <span class="locale">[{{ implode(' - ', $channel_locale) }}]</span>
                                                @endif

                                            </label>

                                            <?php
                                                $configData = core()->getConfigData($name, current($channel_locale),  next($channel_locale));
                                            ?>

                                            @include ($typeView)

                                            <span class="control-error" v-if="errors.has('{{ $name }}')">@{{ errors.first('{!! $name !!}') }}</span>

                                        </div>

                                    @endif
                                @endforeach
                            </div>
                        </accordian>

                    @endforeach

                </div>
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

                window.location.href = "{{ route('admin.configuration.sales.shipping_methods')  }}" + query;
            })
        });
    </script>
@endpush