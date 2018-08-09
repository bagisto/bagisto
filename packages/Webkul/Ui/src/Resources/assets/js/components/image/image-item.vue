<template>
    <div>
        <input type="file" :name="finalInputName" ref="imageInput"/>
    </div>
</template>

<script>
    export default {
        props: {
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

            image: {
                type: Object,
                required: false,
                default: null
            }
        },

        mounted () {
            if(!this.image.id) {
                var element = this.$refs.imageInput;
                element.dispatchEvent(new Event("click"));
                element.click();
            }
        },

        computed: {
            finalInputName () {
                if(this.multiple)
                    return this.inputName + '[]';
                
                return this.inputName;
            }
        }

    }
</script>