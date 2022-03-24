<div {!! $htmlAttributes !!}></div>

@if ($errors->has('g-recaptcha-response'))
    <span class="control-error">
        {{ $errors->first('g-recaptcha-response') }}
    </span>
@endif