@props([
    'name'  => '',
    'entity' => null,
])

<div class="mt-[34px] flex justify-start max-lg:hidden">
    <div class="flex items-center gap-x-3.5">        
        {{ Breadcrumbs::view('shop::partials.breadcrumbs', $name, $entity) }}
    </div>
</div>
