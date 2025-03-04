@props([
    'name'  => '',
    'entity' => null,
])

<div class="mt-4 flex justify-start">
    <div class="flex items-center gap-x-3.5">
        {{ Breadcrumbs::view('shop::partials.breadcrumbs', $name, $entity) }}
    </div>
</div>
