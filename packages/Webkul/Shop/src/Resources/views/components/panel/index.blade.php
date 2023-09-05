<div {{ $attributes->merge(['class' => '']) }}>
    <div {{ $header->attributes->merge(['class' => '']) }}>
        {{ $header }}
    </div>

    <div {{ $body->attributes->merge(['class' => '']) }}>
        {{ $body }}
    </div>
</div>