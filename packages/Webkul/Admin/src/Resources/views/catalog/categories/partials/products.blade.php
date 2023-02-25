{!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.products.controls.before', ['category' => $category]) !!}
                        
<datagrid-plus src="{{ route('admin.catalog.categories.products', $category->id) }}"></datagrid-plus>
                        
{!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.products.controls.before', ['category' => $category]) !!}