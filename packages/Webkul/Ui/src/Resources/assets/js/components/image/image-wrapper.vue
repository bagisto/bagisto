<template>
    <div>
        <div class="image-wrapper">
            <image-item 
                v-for='(image, index) in items' 
                :key='image.id' 
                :image="image" 
                :input-name="inputName" 
                :remove-button-label="removeButtonLabel"
                @onRemoveImage="removeImage($event)"
            ></image-item>
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

            removeButtonLabel: {
                type: String,
                required: false,
                default: 'Remove Image'
            },

            inputName: {
                type: String,
                required: false,
                default: 'attachments'
            },

            images: {
                type: Array,
                required: false,
                default: () => ([])
            }
        },

        data: function() {
            return {
                imageCount: 0,
                items: []
            }
        },

        created () {
            var this_this = this;
            
            this.images.forEach(function(image) {
                this_this.items.push(image)
                this_this.imageCount++;
            });
        },

        methods: {
            createFileType () {
                this.imageCount++;

                this.items.push({'id': 'image_' + this.imageCount});
            },

            removeImage (image) {
                let index = this.items.indexOf(image)

                Vue.delete(this.items, index);
            }
        }

    }
</script>