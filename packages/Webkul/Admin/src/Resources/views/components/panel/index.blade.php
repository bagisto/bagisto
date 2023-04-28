<div {{ $attributes->merge(['class' => 'panel']) }}>
    <div {{ $header->attributes->merge(['class' => 'panel-header']) }}>
        {{ $header }}
    </div>

    <div {{ $body->attributes->merge(['class' => 'panel-body']) }}>
        {{ $body }}
    </div>
</div>