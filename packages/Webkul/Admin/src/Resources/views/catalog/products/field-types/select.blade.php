<div class="control-group" :class="[errors.has('{{ $attribute->code }}') ? 'has-error' : '']">
    <label for="{{ $attribute->code }}" {{ $attribute->is_required ? 'class=required' : '' }}>
        {{ $attribute->admin_name }}
    </label>

    <select v-validate="'{{$validations}}'" class="control" id="{{ $attribute->code }}" name="{{ $attribute->code }}">

        @foreach($attribute->options as $option)
            <option value="{{ $option->id }}" {{ $option->id == $attribute[$attribute->code] ? 'selected' : ''}}>
                {{ $option->admin_name }}
            </option>
        @endforeach

    </select>

    <span class="control-error" v-if="errors.has('{{ $attribute->code }}')">@{{ errors.first('{!! $attribute->code !!}') }}</span>
</div>