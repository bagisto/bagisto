@extends('admin::layouts.content')

@section('content')
    <div class="content">
        <form method="POST" action="{{ route('admin.catalog.products.update', $product->id) }}" @submit.prevent="onSubmit">

            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('admin::app.catalog.products.edit-title') }}</h1>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('admin::app.catalog.products.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                @csrf()

                @foreach($product->attribute_family->attribute_groups as $attributeGroup)
                    @if(count($attributeGroup->attributes))
                        <accordian :title="'{{ __($attributeGroup->name) }}'" :active="true">
                            <div slot="body">

                                @foreach($attributeGroup->attributes as $attribute)

                                    @if(!$product->super_attributes->contains($attribute))
                                    
                                        <?php 
                                            $validations = [];
                                            if($attribute->is_required) {
                                                array_push($validations, 'required');
                                            }

                                            array_push($validations, $attribute->validation);

                                            $validations = implode('|', array_filter($validations));
                                        ?> 

                                        @if(view()->exists($typeView = 'admin::catalog.products.field-types.' . $attribute->type))

                                            @include ($typeView)

                                        @endif

                                    @endif
                                    
                                @endforeach

                            </div>
                        </accordian>
                    @endif
                @endforeach

                @foreach($form_accordians->items as $accordian)

                    @include ($accordian['view'])
                
                @endforeach
            </div>

        </form>
    </div>
@stop