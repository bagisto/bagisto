@extends('shop::layouts.master')

@section('page_title')
    {{ __('shop::app.home.page-title') }}
@endsection

@section('content-wrapper')
    {{-- <ul>
        @foreach ($statesCountries as $key => $stateCountry)
            <li><h3>{{ $key }}</h3></li>
            <select>
            @foreach ($stateCountry as $key1 => $state)
                <option value="{{$key1}}">{{ $state }}</option>
            @endforeach
            </select>
        @endforeach
    </ul> --}}

    <script type="text/x-template" id="country-state-select">
        <div>
            <li>
                <h3>{{ $key }}</h3>
            </li>

            <select v-model="country">

            </select>
        </div>
    </script>

    <script>
        var countryState = @json($statesCountries);

        Vue.component('country-state-select', {

            template: '#country-state-select',

            data: () => ({
                country: '',
                state: ''
            });

            mounted: function() {
                for(country in statesCountries) {
                    console.log(country);
                }
            }
        });
    </script>
@endsection
