{!! view_render_event('bagisto.shop.layout.header.category.before') !!}

<category-nav categories='@json($categories)' url="{{url()->to('/')}}"></category-nav>

{!! view_render_event('bagisto.shop.layout.header.category.after') !!}