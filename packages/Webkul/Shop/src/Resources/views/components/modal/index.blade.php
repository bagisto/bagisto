@props(['header', 'body'])

<modal id="{{ $attributes->get('id') }}" :is-open="{{ $attributes->get('is-open') }}">
    <div slot="header">
        {{ $header }}
    </div>

    <div slot="body">
        {{ $body }}
    </div>
</modal>

@pushOnce('scripts')
    <script type="text/x-template" id="modal-template">
        <div class="modal-container" v-if="isModalOpen">
            <div {{ $header->attributes->merge(['class' => 'modal-header']) }}>
                <slot name="header">
                    Default header
                </slot>

                <i class="icon remove-icon" @click="close"></i>
            </div>

            <div {{ $body->attributes->merge(['class' => 'modal-body']) }}>
                <slot name="body">
                    Default body
                </slot>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('modal', {
            template: '#modal-template',

            props: ['id', 'isOpen'],

            inject: ['$validator'],

            created() {
                this.close();
            },

            computed: {
                isModalOpen() {
                    this.toggleClass();

                    return this.isOpen;
                }
            },

            methods: {
                close() {
                    this.$root.$set(this.$root.modalIds, this.id, false);
                },

                toggleClass() {
                    var body = document.querySelector("body");

                    if(this.isOpen) {
                        body.classList.add("modal-open");
                    } else {
                        body.classList.remove("modal-open");
                    }
                }
            }
        });
    </script>
@endPushOnce
