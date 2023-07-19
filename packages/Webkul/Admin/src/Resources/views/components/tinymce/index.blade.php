<v-tinymce 
    {{ $attributes }}
    data="{{ $slot }}"
>
</v-tinymce>

@pushOnce('scripts')
    @include('admin::layouts.tinymce')

    <script type="text/x-template" id="v-tinymce-template">
        <div>
            <v-field
                type="text"
                :name="name"
                :id="id"
                :rules="rules"
                :label="label"
                :ref="id"
                v-model.html="content"
            >
            </v-field>
        </div>
    </script>

    <script type="module">
        app.component('v-tinymce', {
            template: '#v-tinymce-template',

            props:['data', 'name', 'id', 'rules', 'label', 'value'],

            data() {
                return {
                    content: this.value ?? this.data,
                }
            },

            mounted() {
                this.initTinyMCE();
            },

            methods: {
                initTinyMCE() { 
                    tinyMCEHelper.initTinyMCE({
                        target: this.$refs[this.id].$el,
                        height: 200,
                        width: "100%",
                        plugins: 'image imagetools media wordcount save fullscreen code table lists link hr',
                        toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor alignleft aligncenter alignright alignjustify | link hr |numlist bullist outdent indent  | removeformat | code | table',
                        image_advtab: true,
                        valid_elements : '*[*]',
                        setup: editor => {
                            editor.on('keyup', () => {
                                this.content = editor.getContent();
                            });
                        },
                    });
                }
            }
        })
    </script>
@endPushOnce