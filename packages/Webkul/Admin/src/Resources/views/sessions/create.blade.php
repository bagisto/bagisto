@extends('admin::layouts.master')

@section('content')
    <form method="POST" action="login">
        @csrf

        <div class="element-block">
            <label for="email" class="field-label"></label>
            <div class="field-block">
                <input type="text" class="field" id="email" name="email"/> 
            </div>
        </div>

        <div class="element-block">
            <label for="password" class="field-label"></label>
            <div class="field-block">
                <input type="password" class="field" id="password" name="password"/> 
            </div>
        </div>

        @if (count($errors))
            @foreach ($errors->all() as $error)

                {{ $error }}

            @endforeach
        @endif

        <div class="button-block">
            <button type="submit" class="button">Login</button>
        </div>
    </form>
@stop