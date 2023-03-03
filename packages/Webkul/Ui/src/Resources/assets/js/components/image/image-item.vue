<template>
    <label class="image-item" :for="_uid" v-bind:class="{ 'has-image': imageData.length > 0, 'dropzone': isDragging }">
        <input type="hidden" :name="finalInputName"/>

        <input 
            class="drag-image"
            type="file" 
            v-validate="'mimes:image/*'" 
            accept="image/*" 
            :name="finalInputName" 
            ref="imageInput" 
            :id="_uid" 
            :required="required ? true : false"
            @change="addImageView($event)" 
            @drop="isDragging = false"
            @dragleave="isDragging = false" 
            @dragenter="isDragging = true" />

        <img class="preview" :src="imageData" v-if="imageData.length > 0">

        <label class="remove-image" @click="removeImage()">{{ removeButtonLabel }}</label>
    </label>
</template>

<script>
    export default {
        props: {
            inputName: {
                type: String,
                required: false,
                default: 'attachments'
            },

            removeButtonLabel: {
                type: String,
            },

            image: {
                type: Object,
                required: false,
                default: null
            },

            required: {
                type: Boolean,
                required: false,
                default: false
            }
        },

        data: function() {
            return {
                imageData: '',
                isDragging: false,
            }
        },

        mounted () {
            if(this.image.id && this.image.url) {
                this.imageData = this.image.url;
            }
        },

        computed: {
            finalInputName () {
                return this.inputName + '[' + this.image.id + ']';
            }
        },

        methods: {
            addImageView () {
                var imageInput = this.$refs.imageInput;

                if (imageInput.files && imageInput.files[0]) {
                    if(imageInput.files[0].type.includes('image/')) {
                        var reader = new FileReader();

                        reader.onload = (e) => {
                            this.imageData = e.target.result;
                        }

                        reader.readAsDataURL(imageInput.files[0]);
                    } else {
                        imageInput.value = "";
                        alert('Only images (.jpeg, .jpg, .png, ..) are allowed.');
                    }
                }
            },

            removeImage () {
                this.$emit('onRemoveImage', this.image)
            }
        }
    }
</script>
