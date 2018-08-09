<template>
    <div>
        <div class="image-wrapper">
            <image-item v-for='(image, index) in images' :key='image.uid' :image="image" :input-name="inputName" :multiple="multiple" @onRemoveImage="removImage($event)"></image-item>
        </div>

        <label class="btn btn-lg btn-primary" style="display: inline-block" @click="createFileType">{{ buttonLabel }}</label>
    </div>
</template>

<script>
    export default {
        props: {
            buttonLabel: {
                type: String,
                required: false,
                default: 'Add Image'
            },

            inputName: {
                type: String,
                required: false,
                default: 'attachments'
            },

            multiple: {
                type: [Boolean, String],
                required: false,
                default: true
            },

            images: {
                type: Array,
                required: false,
                default: () => ([])
            }
        },

        methods: {
            createFileType () {
                this.images.push({});
            }
        }

    }
</script>