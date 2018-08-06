<template>
    <div class="preview-image">
        <slot>

        </slot>

        <div class="preview-wrapper">
            <img class="image-preview" :src="sample"/>
        </div>

        <div class="remove-preview">
            <button class="btn btn-md btn-primary" @click.prevent="removePreviewImage">Remove Image</button>
        </div>

    </div>
</template>
<script>
    export default{

        data: function() {
            return {
                sample: "",
                image_file: "",
                file: null,
                newImage:"",
            };
        },

        mounted: function() {

            this.sample = "";
            var element = this.$el.getElementsByTagName("input")[0];
            var this_this = this;
            element.onchange = function() {
                var fReader = new FileReader();
                fReader.readAsDataURL(element.files[0]);
                fReader.onload = function(event) {
                    this.img = document.getElementsByTagName("input")[0];
                    this.img.src = event.target.result;
                    this_this.newImage = this.img.src;
                    this_this.changePreview();
                };
            }
        },

        methods: {
            removePreviewImage: function() {
                this.sample = "";
            },

            changePreview: function(){
                this.sample = this.newImage;
            }
        },

        computed: {
            getInputImage() {
                console.log(this.imageData);
            }
        }
    }
</script>
<style>
    .preview-wrapper{
        height:200px;
        width:200px;
        padding:5px;
    }
    .image-preview{
        height:190px;
        width:190px;
    }
</style>
