{!! view_render_event('bagisto.admin.catalog.product.edit.form.rma_rules.controls.before', ['product' => $product]) !!}

<v-rma-rules>
    <x-admin::form.control-group class="last:!mb-0">
        <x-admin::form.control-group.label>
            {!! $attribute->admin_name . ($attribute->is_required ? '<span class="required"></span>' : '') !!}

            @if (
                $attribute->value_per_channel
                && $channels->count() > 1
            )
                <span 
                    class="rounded border border-gray-200 bg-gray-100 px-1 py-0.5 text-[10px] font-semibold leading-normal text-gray-600"
                    v-pre
                >
                    {{ $currentChannel->name }}
                </span>
            @endif

            @if ($attribute->value_per_locale)
                <span
                    class="rounded border border-gray-200 bg-gray-100 px-1 py-0.5 text-[10px] font-semibold leading-normal text-gray-600"
                    v-pre
                >
                    {{ $currentLocale->name }}
                </span>
            @endif
        </x-admin::form.control-group.label>

        @include ('admin::catalog.products.edit.controls', [
            'attribute' => $attribute,
            'product'   => $product,
        ])

        <x-admin::form.control-group.error :control-name="$attribute->code . (in_array($attribute->type, ['multiselect', 'checkbox']) ? '[]' : '')" />
    </x-admin::form.control-group>
</v-rma-rules>

{!! view_render_event('bagisto.admin.catalog.product.edit.form.rma_rules.controls.after', ['product' => $product]) !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-rma-rules-template"
    >
        <div v-show="allowRMA">
            <slot></slot>
        </div>
    </script>

    <script type="module">
        app.component('v-rma-rules', {
            template: '#v-rma-rules-template',

            data() {
                return {
                    allowRMA: "{{ (boolean) $product->allow_rma }}",
                }
            },

            mounted: function() {
                let self = this;

                document.getElementById('allow_rma').addEventListener('change', function(e) {
                    self.allowRMA = e.target.checked;
                });
            }
        });
    </script>
@endpushOnce