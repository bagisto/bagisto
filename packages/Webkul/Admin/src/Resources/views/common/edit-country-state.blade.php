<div class="control-group">
    <?php $selectedCountry = old('country') ?: $model->country ?>

    <label for="country">{{ __('admin::app.common.country') }}</label>
    
    <select class="control" id="country" name="country">

        @foreach(core()->countries() as $country)

            <option value="{{ $country->code }}" {{ $selectedCountry == $country->code ? 'selected' : '' }}>
                {{ $country->name }}
            </option>

        @endforeach

    </select>
</div>

<div class="control-group">
    <label for="state">{{ __('admin::app.common.state') }}</label>
    <input class="control" id="state" name="state" value="{{ old('state') ?: $model->state }}"/>
</div>

@push('scripts')

    

@endpush