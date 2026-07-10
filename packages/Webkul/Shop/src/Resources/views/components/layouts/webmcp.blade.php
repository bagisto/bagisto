{!! view_render_event('bagisto.shop.layout.webmcp.before') !!}

<form
    action="{{ route('shop.webmcp.product') }}"
    method="GET"
    toolname="view_product"
    tooldescription="{{ trans('shop::app.components.layouts.webmcp.view-product') }}"
    toolautosubmit
>
    <input
        type="text"
        name="query"
        aria-label="{{ trans('shop::app.components.layouts.webmcp.view-product-query') }}"
        toolparamdescription="{{ trans('shop::app.components.layouts.webmcp.view-product-query') }}"
    >

    <button type="submit" class="hidden" aria-hidden="true"></button>
</form>

<form
    action="{{ route('shop.webmcp.wishlist.add') }}"
    method="GET"
    toolname="add_to_wishlist"
    tooldescription="{{ trans('shop::app.components.layouts.webmcp.add-to-wishlist') }}"
    toolautosubmit
>
    <input
        type="text"
        name="query"
        aria-label="{{ trans('shop::app.components.layouts.webmcp.add-to-wishlist-query') }}"
        toolparamdescription="{{ trans('shop::app.components.layouts.webmcp.add-to-wishlist-query') }}"
    >

    <button type="submit" class="hidden" aria-hidden="true"></button>
</form>

<form
    action="{{ route('shop.customers.account.wishlist.index') }}"
    method="GET"
    toolname="view_wishlist"
    tooldescription="{{ trans('shop::app.components.layouts.webmcp.view-wishlist') }}"
    toolautosubmit
>
    <button type="submit" class="hidden" aria-hidden="true"></button>
</form>

<form
    action="{{ route('shop.checkout.cart.index') }}"
    method="GET"
    toolname="view_cart"
    tooldescription="{{ trans('shop::app.components.layouts.webmcp.view-cart') }}"
    toolautosubmit
>
    <button type="submit" class="hidden" aria-hidden="true"></button>
</form>

<form
    action="{{ route('shop.checkout.onepage.index') }}"
    method="GET"
    toolname="proceed_to_checkout"
    tooldescription="{{ trans('shop::app.components.layouts.webmcp.proceed-to-checkout') }}"
    toolautosubmit
>
    <button type="submit" class="hidden" aria-hidden="true"></button>
</form>

{{--
    Bridge the declarative `<form toolname>` markup above to the browser WebMCP
    API (https://webmachinelearning.github.io/webmcp/). Each form becomes a tool
    registered via `navigator.modelContext.provideContext()`; the tool's execute
    callback fills the form from the agent-supplied arguments and submits it,
    reusing the existing server-side `/webmcp/*` handlers. No-op in browsers that
    do not expose the WebMCP API.
--}}
@pushOnce('scripts')
    <script>
        (function () {
            function registerWebMcpTools() {
                if (
                    ! ('modelContext' in navigator)
                    || typeof navigator.modelContext.provideContext !== 'function'
                ) {
                    return;
                }

                var tools = [];

                document.querySelectorAll('form[toolname]').forEach(function (form) {
                    var name = form.getAttribute('toolname');

                    if (! name) {
                        return;
                    }

                    var properties = {};
                    var required = [];

                    form.querySelectorAll('input[name], textarea[name], select[name]').forEach(function (field) {
                        var param = field.getAttribute('name');

                        properties[param] = {
                            type: 'string',
                            description: field.getAttribute('toolparamdescription')
                                || field.getAttribute('aria-label')
                                || param,
                        };

                        if (field.hasAttribute('required')) {
                            required.push(param);
                        }
                    });

                    tools.push({
                        name: name,
                        description: form.getAttribute('tooldescription') || name,
                        inputSchema: {
                            type: 'object',
                            properties: properties,
                            required: required,
                        },
                        execute: function (args) {
                            args = args || {};

                            Object.keys(args).forEach(function (key) {
                                var field = form.querySelector('[name="' + key + '"]');

                                if (field) {
                                    field.value = args[key];
                                }
                            });

                            form.submit();

                            return {
                                content: [
                                    { type: 'text', text: 'Executed "' + name + '".' },
                                ],
                            };
                        },
                    });
                });

                if (tools.length) {
                    navigator.modelContext.provideContext({ tools: tools });
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', registerWebMcpTools);
            } else {
                registerWebMcpTools();
            }
        })();
    </script>
@endPushOnce

{!! view_render_event('bagisto.shop.layout.webmcp.after') !!}
