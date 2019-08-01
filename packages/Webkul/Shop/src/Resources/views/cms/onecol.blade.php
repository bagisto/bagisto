@extends('shop::layouts.master')

@section('content-wrapper')
    <div class="static-container one-column">
        <h1>One Column Layout</h1>

        {{ $data['content'] }}
    </div>
@endsection