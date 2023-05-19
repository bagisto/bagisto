@props(['title', 'header', 'body'])

<accordion title="{{ $title }}">
    <div slot="header">
        {{ $header }}
    </div>

    <div slot="body">
        {{ $body }}
    </div>
</accordion>

@pushOnce('scripts')
    <script type="text/x-template" id="accordion-template">
        <div
            :class="[
                'accordion',
                isActive ? 'active' : '',
                className,
                ! isActive && hasError ? 'error' : ''
            ]"
            :id="id"
        >
            <div {{ $header->attributes->merge(['class' => 'accordion-header']) }} @click="toggle">
                <slot name="header">
                    @{{ title }}
                    <i :class="['icon', iconClass]"></i>
                </slot>
            </div>

            <div {{ $body->attributes->merge(['class' => 'accordion-content']) }} ref="controls">
                <slot name="body"></slot>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('accordion', {
            template: '#accordion-template',

            inject: ['$validator'],

            props: {
                title: String,

                id: String,

                className: String,

                active: Boolean,

                downIconClass: {
                    type: String,
                    default: "accordion-down-icon"
                },

                upIconClass: {
                    type: String,
                    default: "accordion-up-icon"
                }
            },

            data() {
                return {
                    isActive: false,

                    hasError: false
                };
            },

            mounted() {
                this.addHasErrorClass();

                eventBus.$on("onFormError", this.addHasErrorClass);

                this.isActive = this.active;
            },

            methods: {
                toggle() {
                    this.isActive = ! this.isActive;
                },

                addHasErrorClass() {
                    let self = this;

                    setTimeout(function() {
                        $(self.$el)
                            .find(".control-group")
                            .each(function(element) {
                                if ($(element).hasClass("has-error")) {
                                    self.hasError = true;
                                }
                            });
                    }, 0);
                }
            },

            computed: {
                iconClass() {
                    return {
                        [this.downIconClass]: ! this.isActive,
                        [this.upIconClass]: this.isActive
                    };
                }
            }
        });
    </script>
@endPushOnce
