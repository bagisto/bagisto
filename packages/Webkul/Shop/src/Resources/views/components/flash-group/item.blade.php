<flash-item
    v-for='flash in flashes'
    :key='flash.uid'
    :flash="flash"
    @onRemove="remove($event)"
></flash-item>

@pushOnce('scripts')
    <script type="text/x-template" id="flash-item-template">
        <div class="alert" v-bind:class="flash.type">
            <span class="icon white-cross-sm-icon" @click="remove"></span>

            <p>@{{ flash.message }}</p>
        </div>
    </script>

    <script type="module">
        app.component('flash-item', {
            template: '#flash-item-template',

            props: ['flash'],

            created() {
                var self = this;

                setTimeout(function() {
                    self.remove()
                }, 5000)
            },

            methods: {
                remove() {
                    this.$emit('onRemove', this.flash)
                }
            }
        });
    </script>
@endpushOnce
