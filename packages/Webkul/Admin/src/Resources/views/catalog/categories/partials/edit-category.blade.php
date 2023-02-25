<div class="form-container">
    @csrf()
    
    <input name="_method" type="hidden" value="PUT">
    
    {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.general.before', ['category' => $category]) !!}
    
    <accordian title="{{ __('admin::app.catalog.categories.general') }}" :active="true">
        <div slot="body">
            {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.general.controls.before', ['category' => $category]) !!}
            
            <div class="control-group" :class="[errors.has('{{$locale}}[name]') ? 'has-error' : '']">
                <label for="name" class="required">{{ __('admin::app.catalog.categories.name') }}
                    <span class="locale">[{{ $locale }}]</span>
                </label>

                <input 
                    type="text" v-validate="'required'" 
                    name="{{$locale}}[name]"
                    value="{{ old($locale)['name'] ?? ($category->translate($locale)['name'] ?? '') }}"
                    class="control" 
                    id="name" 
                    data-vv-as="&quot;{{ __('admin::app.catalog.categories.name') }}&quot;"
                />

                <span class="control-error" v-text="errors.first('{!!$locale!!}[name]')" v-if="errors.has('{{$locale}}[name]')"></span>
            </div>
            
            <div class="control-group" :class="[errors.has('status') ? 'has-error' : '']">
                <label for="status" class="required">{{ __('admin::app.catalog.categories.visible-in-menu') }}</label>
                <select class="control" v-validate="'required'" id="status" name="status" data-vv-as="&quot;{{ __('admin::app.catalog.categories.visible-in-menu') }}&quot;">
                    <option value="1" {{ $category->status ? 'selected' : '' }}>
                        {{ __('admin::app.catalog.categories.yes') }}
                    </option>
                    
                    <option value="0" {{ $category->status ? '' : 'selected' }}>
                        {{ __('admin::app.catalog.categories.no') }}
                    </option>
                </select>
                
                <span class="control-error" v-if="errors.has('status')">@{{ errors.first('status') }}</span>
            </div>
            
            <div class="control-group" :class="[errors.has('position') ? 'has-error' : '']">
                <label for="position" class="required">{{ __('admin::app.catalog.categories.position') }}</label>
                <input type="text" v-validate="'required|numeric'" class="control" id="position" name="position" value="{{ old('position') ?: $category->position }}" data-vv-as="&quot;{{ __('admin::app.catalog.categories.position') }}&quot;"/>
                
                <span class="control-error" v-if="errors.has('position')">@{{ errors.first('position') }}</span>
            </div>
            
            {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.general.controls.after', ['category' => $category]) !!}
        </div>
    </accordian>

    {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.general.after', ['category' => $category]) !!}

    {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.description_images.before', ['category' => $category]) !!}
    
    <accordian title="{{ __('admin::app.catalog.categories.description-and-images') }}" :active="false">
        <div slot="body">
            {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.description_images.controls.before', ['category' => $category]) !!}
            
            <div class="control-group" :class="[errors.has('display_mode') ? 'has-error' : '']">
                <label for="display_mode" class="required">{{ __('admin::app.catalog.categories.display-mode') }}</label>
                <select class="control" v-validate="'required'" id="display_mode" name="display_mode" data-vv-as="&quot;{{ __('admin::app.catalog.categories.display-mode') }}&quot;">
                    <option value="products_and_description" {{ $category->display_mode == 'products_and_description' ? 'selected' : '' }}>
                        {{ __('admin::app.catalog.categories.products-and-description') }}
                    </option>
                    
                    <option value="products_only" {{ $category->display_mode == 'products_only' ? 'selected' : '' }}>
                        {{ __('admin::app.catalog.categories.products-only') }}
                    </option>
                    
                    <option value="description_only" {{ $category->display_mode == 'description_only' ? 'selected' : '' }}>
                        {{ __('admin::app.catalog.categories.description-only') }}
                    </option>
                </select>
                
                <span class="control-error" v-if="errors.has('display_mode')">@{{ errors.first('display_mode') }}</span>
            </div>
            
            <description></description>
            
            <div class="control-group {!! $errors->has('image.*') ? 'has-error' : '' !!}">
                <label>{{ __('admin::app.catalog.categories.image') }}</label>
                <image-wrapper button-label="{{ __('admin::app.catalog.products.add-image-btn-title') }}" input-name="image" :multiple="false"  :images='"{{ $category->image_url }}"'></image-wrapper>
                
                <span class="control-error" v-if="{!! $errors->has('image.*') !!}">
                    @foreach ($errors->get('image.*') as $key => $message)
                        @php echo str_replace($key, 'Image', $message[0]); @endphp
                    @endforeach
                </span>
                
                <label>{{ __('admin::app.catalog.categories.category_banner') }}</label>
                <large-image-wrapper button-label="{{ __('admin::app.catalog.products.add-image-btn-title') }}" input-name="category_banner" :multiple="false" :images='"{{ $category->banner_url }}"'></large-image-wrapper>
                
                <span class="control-error" v-if="{!! $errors->has('image.*') !!}">
                    @foreach ($errors->get('image.*') as $key => $message)
                        @php echo str_replace($key, 'Image', $message[0]); @endphp
                    @endforeach
                </span>
                
                <span class="control-info mt-10">{{ __('admin::app.catalog.categories.banner_size') }}</span>
            </div>
            
            {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.description_images.controls.after', ['category' => $category]) !!}
        </div>
    </accordian>
    
    {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.description_images.after', ['category' => $category]) !!}
    
    @if ($categories->count())
        {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.parent_category.before', ['category' => $category]) !!}
        
        <accordian title="{{ __('admin::app.catalog.categories.parent-category') }}" :active="false">
            <div slot="body">
                {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.parent_category.controls.before', ['category' => $category]) !!}
                
                <tree-view value-field="id" name-field="parent_id" input-type="radio" items='@json($categories)' value='@json($category->parent_id)' fallback-locale="{{ config('app.fallback_locale') }}"></tree-view>
                
                {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.parent_category.controls.before', ['category' => $category]) !!}
            </div>
        </accordian>
        
        {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.parent_category.after', ['category' => $category]) !!}
    @endif
    
    <accordian title="{{ __('admin::app.catalog.categories.filterable-attributes') }}" :active="false">
        <div slot="body">
            @php $selectedaAtributes = old('attributes') ?? $category->filterableAttributes->pluck('id')->toArray() @endphp
            
            <div class="control-group multi-select" :class="[errors.has('attributes[]') ? 'has-error' : '']">
                <label for="attributes" class="required">{{ __('admin::app.catalog.categories.attributes') }}</label>
                <select class="control" name="attributes[]" v-validate="'required'" data-vv-as="&quot;{{ __('admin::app.catalog.categories.attributes') }}&quot;" multiple>
                    @foreach ($attributes as $attribute)
                    <option value="{{ $attribute->id }}" {{ in_array($attribute->id, $selectedaAtributes) ? 'selected' : ''}}>
                        {{ $attribute->name ? $attribute->name : $attribute->admin_name }}
                    </option>
                    @endforeach
                </select>
                
                <span class="control-error" v-if="errors.has('attributes[]')">
                    @{{ errors.first('attributes[]') }}
                </span>
            </div>
        </div>
    </accordian>
    
    {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.seo.before', ['category' => $category]) !!}
    <accordian title="{{ __('admin::app.catalog.categories.seo') }}" :active="false">
        <div slot="body">
            {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.seo.controls.before', ['category' => $category]) !!}
            
            <div class="control-group">
                <label for="meta_title">{{ __('admin::app.catalog.categories.meta_title') }}
                    <span class="locale">[{{ $locale }}]</span>
                </label>
                
                <input type="text" class="control" id="meta_title" name="{{$locale}}[meta_title]" value="{{ old($locale)['meta_title'] ?? ($category->translate($locale)['meta_title'] ?? '') }}"/>
            </div>
            
            <div class="control-group" :class="[errors.has('{{$locale}}[slug]') ? 'has-error' : '']">
                <label for="slug" class="required">{{ __('admin::app.catalog.categories.slug') }}
                    <span class="locale">[{{ $locale }}]</span>
                </label>
                
                <input type="text" v-validate="'required'" class="control" id="slug" name="{{$locale}}[slug]" value="{{ old($locale)['slug'] ?? ($category->translate($locale)['slug'] ?? '') }}" data-vv-as="&quot;{{ __('admin::app.catalog.categories.slug') }}&quot;" v-slugify/>
                <span class="control-error" v-if="errors.has('{{$locale}}[slug]')">@{{ errors.first('{!!$locale!!}[slug]') }}</span>
            </div>
            
            <div class="control-group">
                <label for="meta_description">{{ __('admin::app.catalog.categories.meta_description') }}
                    <span class="locale">[{{ $locale }}]</span>
                </label>
                
                <textarea class="control" id="meta_description" name="{{$locale}}[meta_description]">{{ old($locale)['meta_description'] ?? ($category->translate($locale)['meta_description'] ?? '') }}</textarea>
            </div>
            
            <div class="control-group">
                <label for="meta_keywords">{{ __('admin::app.catalog.categories.meta_keywords') }}
                    <span class="locale">[{{ $locale }}]</span>
                </label>
                
                <textarea class="control" id="meta_keywords" name="{{$locale}}[meta_keywords]">{{ old($locale)['meta_keywords'] ?? ($category->translate($locale)['meta_keywords'] ?? '') }}</textarea>
            </div>
            
            {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.seo.controls.after', ['category' => $category]) !!}
        </div>
    </accordian>
    {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.seo.after', ['category' => $category]) !!}
</div>